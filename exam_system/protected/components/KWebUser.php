<?php

/**
 * Class overrides Yii's class CWebUser that represents 
 * the persistent state for a Web application user. 
 * 
 * for further documentation see: yiiframework.com/doc/api/1.1/CWebUser
 * 
 * for checking if user has access to a role call: Yii::app()->user->checkAccess('role')
 */
class KWebUser extends CWebUser 
{
	/**
	 * user status constants 
	 * GUEST
	 * DONT_EXISTS
	 * DELETED
	 * INACTIVE
	 * ACTIVE
	 */

	
	/**
	 * @var int guest user
	 */
	private $STATUS_GUEST = -3;

	public function getSTATUS_GUEST()
	{
		return $this->STATUS_GUEST;
	}

	
	/**
	 * @var int user do not exist in database
	 */
	private $STATUS_DONT_EXIST = -2;

	public function getSTATUS_DONT_EXIST()
	{
		return $this->STATUS_DONT_EXIST;
	}

	
	/**
	 * @var int user is deleted
	 */
	private $STATUS_DELETED = -1;

	public function getSTATUS_DELETED()
	{
		return $this->STATUS_DELETED;
	}

	
	/**
	 * @var int user is not active
	 */
	private $STATUS_INACTIVE = 0;

	public function getSTATUS_INACTIVE()
	{
		return $this->STATUS_INACTIVE;
	}

	
	/**
	 * @var int user is active
	 */
	private $STATUS_ACTIVE = 1;

	public function getSTATUS_ACTIVE()
	{
		return $this->STATUS_ACTIVE;
	}
	
	
	
	/**
	 * @var string the ID of the database connection application component. Defaults to 'db'.
	 */
	public $connectionID='db';
	
	
	
	/**
	 * user table name
	 */
	public $userTable = 'user';
	
	
	
	/**
	 * install component database tables
	 * check for every table before install
	 * 
	 * @return boolean
	 */
	public function install()
	{
		// initialize db connection
		$this->getDbConnection();
		$this->_db->schema->refresh();
		
		// check if table is already installed
		$tableName = $this->_db->prefixTable($this->userTable);
		if( !$this->_db->tableExist($tableName) )
		{
			$driver = Yii::app()->db->getDriverName();
			
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->_db->createCommand("
						CREATE TABLE {{".$this->userTable."}} (
							`id` int(11) not null auto_increment,
							`login` varchar(255) collate utf8_unicode_ci not null,
							`display_name` varchar(255) collate utf8_unicode_ci not null default '',
							`email` varchar(255) collate utf8_unicode_ci not null,
							`password` varchar(128) collate utf8_unicode_ci not null,
							
							`is_active` boolean not null default false,
							`active_from` int(11) not null default 0,
							`active_to` int(11) not null default 0,
							`is_deleted` boolean not null default false,
							
							`create_date` int(11) not null,
							`create_user` int(11) not null,
							`last_update_date` timestamp default current_timestamp() on update current_timestamp(),
							`last_update_user` int(11) not null,
							
							`last_login_date` int(11),
							
							primary key (`id`),
							unique (`login`),
							unique (`email`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users';
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KWebUser component does not work with {db} databases.',
						array('{db}'=>$driver)));
					break;
			}
			
			if(isset($sequence))
				$sequence->execute();
			
			$command->execute();
			
			// check, if was successful
			$this->_db->schema->refresh();
			if( $this->_db->tableExist($tableName) )
				return true;
			else
				return false;
		}
		return true;
	}
	
	
	
	/**
	 * 
	 */
	public function reinstall()
	{
		if($this->uninstall())
		{
			if($this->install())
			{
				return true;
			}
		}
		return false;
	}
	
	
	
	/**
	 * 
	 */
	public function uninstall()
	{
		// initialize db connection
		$this->getDbConnection();
		$this->_db->schema->refresh();
		
		// check if table is already installed
		$tableName = $this->_db->prefixTable($this->userTable);
		if( $this->_db->tableExist($tableName) )
		{
			$driver = Yii::app()->db->getDriverName();
			
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->_db->createCommand("
						DROP TABLE {{".$this->userTable."}};
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KWebUser component does not work with {db} databases.',
						array('{db}'=>$driver)));
					break;
			}
			
			if(isset($sequence))
				$sequence->execute();
			
			$command->execute();
			
			// check, if was successful
			$this->_db->schema->refresh();
			if( !$this->_db->tableExist($tableName) )
				return true;
			else
				return false;
		}
		return true;
	}
	
	
	
	/**
	 * Database connection
	 */
	private $_db;

	
	
	/**
	 * Returns the DB connection used for the current component.
	 * @return CDbConnection the DB connection used for the message source.
	 * @since 1.1.5
	 */
	public function getDbConnection()
	{
		if($this->_db===null)
		{
			$this->_db=Yii::app()->getComponent($this->connectionID);
			if(!$this->_db instanceof KDbConnection)
				throw new CException(Yii::t('yii','KWebUser.connectionID is invalid. Please make sure "{id}" refers to a valid database application component.',
					array('{id}'=>$this->connectionID)));
		}
		return $this->_db;
	}

	
	
	/**
	 * 
	 */
	public function init()
	{
		parent::init();
		
		if(defined('YII_DEBUG') && YII_DEBUG===true)
			$result = $this->install();
	}
	
	
	
	/**
	 * Overrides a Yii method that is used for roles in controllers (accessRules).
	 *
	 * @param string $operation Name of the operation required (here, a role).
	 * @param mixed $params (opt) Parameters for this operation, usually the object to access.
	 * @return bool Permission granted?
	 * 
	 * TODO:
	 */
	public function checkAccess( $operation, $params = array( ) )
	{
		if( empty( $this->id ) || $this->isGuest )
		{
			// Not identified => no rights
			return false;
		}

		// if admin -> return true
		if( $this->id == '1' )
			return true;
		
		//
		// TODO:
		// write acces rules
		//
		$authManager = Yii::app()->authManager;
		$user = Yii::app()->user;
		
		$access = false;
		
		$access = $authManager->checkAccess($operation, $user->id, $params);

		return $access;

		
	}
	
	
	
	/**
	 * Function checks, if user (with given id):
	 * - is guest,
	 * - exists in database,
	 *   - if exists, checks
	 *     - if is active/inactive/deleted
	 * 
	 * @return int user status code
	 * 		STATUS_GUEST - if user is guest (not logged in)
	 * 		STATUS_DONT_EXIST - if user does not exist in database
	 * 		STATUS_DELETED - if user exists but was deleted
	 * 		STATUS_INACTIVE - if user exists but is not active
	 * 		STATUS_ACTIVE - if user exists and is active
	 * 		false if a kind of error occured
	 * returned value should be compared with one of read-only class members
	 * you can do it like this:
	 * if(Yii::app()->user->checkUserStatus()===Yii::app()->user->STATUS_GUEST)
	 * {
	 * 		// your code
	 * }
	 */
	
	/**
	 * checks status of user (with given id)
	 * status can be:
	 * - active
	 * - inactive
	 * - deleted
	 * - doesn't exist
	 * 
	 * @param type $id
	 * @return boolean
	 * @throws CException 
	 */
	public function checkUserStatus($id)
	{
		if( ((int)$id) != $id )
			throw new CException(Yii::t('yii','KWebUser component: wrong user id.',
					array('{id}'=>$this->connectionID)));
		

		// return for admin
		if( $id == 1 )
			return $this->STATUS_ACTIVE;
		
		
		$user = Yii::app()->db->createCommand()
			->select('is_active, active_from, active_to, is_deleted')
			->from('{{'.$this->userTable.'}}')
			->where('id=:id', array( ':id'=> $id ))
			->queryRow();

		if( $user == null )
		{
			return $this->STATUS_DONT_EXIST;
		}

		if( $user['is_deleted'] == true )
		{
			return $this->STATUS_DELETED;
		}

		if( $user['is_active'] == false )
		{
			return $this->STATUS_INACTIVE;
		}
		else
		{
			$now = time();
			
			$active_from = (int)$user['active_from'];
			$active_to = (int)$user['active_to'];
			
			// this is error situation
			if( $user['active_from'] == '0' )
				return false;

			if( $user['active_to'] == '0' )
			{
				if( $active_from > $now )
				{
					// not active (yet)
					return $this->STATUS_INACTIVE;
				}
				else
				{
					// active to infinity
					return $this->STATUS_ACTIVE;
				}
			}
			else
			{
				if( $active_to < $now )
				{
					return $this->STATUS_INACTIVE;
				}
				else
				{
					if( $active_from < $now )
					{
						return $this->STATUS_ACTIVE;
					}
					else
					{
						return $this->STATUS_INACTIVE;
					}
				}
			}

			return false;
		}

		// some kind of error if this got here
		return false;
	}
	
	
	
	/**
	 * checks status of current user
	 * can be:
	 * - guest
	 * - active
	 * - inactive
	 * - deleted
	 */
	public function checkStatus()
	{
		if( empty( $this->id ) || $this->isGuest )
		{
			// user is a guest or id does not exist
			return $this->STATUS_GUEST;
		}
		return $this->checkUserStatus($this->id);
	}
	
	
	
	/**
	 * checks for any user
	 */
	public function isUserActive($id)
	{
		return $this->checkUserStatus($id) === $this->STATUS_ACTIVE;
	}
	
	
	
	/**
	 * check for current user
	 * @return type 
	 */
	public function isActive()
	{
		if( $this->isGuest )
			return false;
		
		return $this->isUserActive($this->id);
	}

	
	
	/**
	 * check for any user+
	 */
	public function isUserDeleted($id)
	{
		return $this->checkUserStatus($id) === $this->STATUS_DELETED;
	}
	
	
	
	/**
	 * check for current user
	 */
	public function isDeleted()
	{
		if( $this->isGuest )
			return false;
		
		return $this->isUserDeleted($this->id);
	}
	
	
	
	/**
	 * checks if user with given id exist in db
	 */
	public function userExist($id)
	{
		return $this->checkUserStatus($id) !== $this->STATUS_DONT_EXIST;
	}
	
	
	
	/**
	 * 
	 */
	public function checkUserStatus_string($id)
	{
		$status = $this->checkUserStatus($id);
		
		switch ($status)
		{
			case -3 :
				return 'Gość';
				break;
			case -2 :
				return 'Nie ma takiego użytkownika';
				break;
			case -1 :
				return 'Usunięty';
				break;
			case 0 :
				return 'Nieaktywny';
				break;
			case 1 :
				return 'Aktywny';
				break;
			default :
				return 'Error';
		}
	}
}