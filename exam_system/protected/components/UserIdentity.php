<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * user's id
	 */
	private $_id;
	
	
	/**
	 * user's login
	 */
	private $_login;
	
	
	/**
	 * users displayed name
	 */
	private $_displayName;
	
	
	/**
	 * user's last login date
	 */
	private $_lastLoginDate;
	
	
	
	/**
	* Authenticates a user using the User data model.
	* @return boolean whether authentication succeeds.
	*/
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('login'=>  htmlspecialchars($this->username)));
		
		if( $user == null )
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else
		{
			if( $user->password != $user->encrypt($this->password) )
			{
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				// user id (number in database)
				$this->_id = $user->id;
				
				// login
				$this->_login = $user->login;
				$this->setState('login', $this->_login);
				
				// user name (display name)
				$this->_displayName = ($user->display_name != '') ? $user->display_name : $user->login;
				$this->setState('displayName', $this->_displayName);
				
				// set user last login time in user session 
				// (to get it later call: Yii::app()->user->lastLoginDate;
				// !important -> user must be logged in (!Yii::app()->user->isGuest)
				// calling for guest user will cause error
				$this->_lastLoginDate = $user->last_login_date;
				$this->setState('lastLoginDate', $this->_lastLoginDate);
				
				
				
				
			//	$this->_is_active_flag = $user->is_active;
			//	$this->setState('is_active_flag', $this->_is_active_flag);
				
			//	$this->_active_from = $user->active_from;
			//	$this->setState('active_from', $this->_active_from);
				
			//	$this->_active_to = $user->active_to;
			//	$this->setState('active_to', $this->_active_to);
				
			//	$this->_is_deleted = $user->is_deleted;
			//	$this->setState('is_deleted', $this->_is_deleted);
				
				//$this->setState('roles', $record->roles); 
				
				
				 
				
				$this->errorCode=self::ERROR_NONE;
			}
		}
		return !$this->errorCode;
	}
	
	
	
	public function getId() 
	{
		return $this->_id;
	}
	
	
	
	public function getLogin()
	{
		return $this->_login;
	}
	
	
	
	public function getDisplayName()
	{
		return $this->_userName;
	}
	
	
	
	public function getLastLoginDate()
	{
		return $this->_lastLoginDate;
	}
}