<?php
/**
 * Enables to put settings variables in database
 * 
 */
class KSettings extends KApplicationComponent
{
	/**
	 * settings database table name
	 */
	public $settingsTable = 'settings';
	// table name with prefix
	private $tableName;
	
	
	/**
	 * 
	 */
	public function init()
	{
		parent::init();
		
		// initialize db connection
		$this->getDbConnection();
		
		$this->tableName = $this->_db->prefixTable($this->settingsTable);
	}
	
	
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
		$tableName = $this->_db->prefixTable($this->settingsTable);
		if( !$this->_db->tableExist($tableName) )
		{
			$driver = Yii::app()->db->getDriverName();
			
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->_db->createCommand("
						CREATE TABLE {{".$this->settingsTable."}} (
							`category` varchar(255) collate utf8_unicode_ci not null,
							`name` varchar(255) collate utf8_unicode_ci not null,
							`value` text collate utf8_unicode_ci not null,
							`type` varchar(16) collate utf8_unicode_ci not null,
							
							
							primary key (`category`, `name`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='settings';
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KSettings component does not work with {db} databases.',
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
		$tableName = $this->_db->prefixTable($this->settingsTable);
		if( $this->_db->tableExist($tableName) )
		{
			$driver = Yii::app()->db->getDriverName();
			
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->_db->createCommand("
						DROP TABLE {{".$this->settingsTable."}};
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KSettings component does not work with {db} databases.',
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
	 * data can be inserted in few types: string, int, float, boolean, as serialized data
	 * 
	 * @param $category
	 * @param $name
	 * @param value
	 * @param $type - type can be: string, int, float, boolean, serialize
	 * 
	 * @return array with new settings or false
	 */
	public function insert( $category, $name, $value, $type = 'serialize' )
	{
		$tableName = $this->tableName;
		// store it temporary
		$val = $value;
	
		if( !$this->_db->tableExist($tableName) )
			throw new CException(Yii::t('yii','KSettings component is not installed.'));
		
		
		// validate params
		
		// validate category
		if( $category == '' 
			|| $category == 'yii'
			|| $category == 'zii'
			|| strlen($category) > 254 )
		{
			throw new CHttpException(409, 'KSettings component -> insert: wrong category param value');
			return false;
		}
		
		// validate name
		if( $name == '' 
			|| $name == 'yii'
			|| $name == 'zii' 
			|| !is_string($category)
			|| strlen($name) > 254 )
		{
			throw new CHttpException(409, 'KSettings component -> insert: wrong name param value');
			return false;
		}
		
		// validate type
		if( !( $type == 'string'
			|| $type == 'int'
			|| $type == 'float'
			|| $type == 'boolean'
			|| $type == 'serialize') )
		{
			throw new CHttpException(409, 'KSettings component -> insert: wrong type param value');
			return false;
		}
		
		// prepare value
		switch ($type)
		{
			case 'string':
				if( !is_string($value) )
					throw new CHttpException(409, 'KSettings component -> insert: wrong value param value for string type');
				$value = (string)$value;
				break;
				
			case 'int':
				// check if it really is int
				if( !is_int($value) )
					throw new CHttpException(409, 'KSettings component -> insert: wrong value param value for int type');
				$value = (string)$value;
				break;
				
			case 'float':
				if( !(is_float($value) || is_int($value)) )
					throw new CHttpException(409, 'KSettings component -> insert: wrong value param value for float type');
				$value = (string)$value;
				break;
				
			case 'boolean':
				if( !is_bool($value) )
					throw new CHttpException(409, 'KSettings component -> insert: wrong value param value for boolean type');
				$value = $value ? '1' : '0';
				break;
				
			case 'serialize':
				// TODO: ?allow to serialize everything?
				$value = serialize($value);
				break;
			
			default:
				throw new CHttpException(409, 'KSettings component -> insert: wrong type param value');
				break;
		}
		
		
		$insert = $this->_db->createCommand("
			INSERT INTO ". $tableName . " (`category`, `name`, `value`, `type`) 
			VALUES ('".$category."', '".$name."', '".$value."', '".$type."');
		");
		
		$result = null;
		try
		{
			$result = $insert->execute();
			
		}
		catch (CDbException $e)
		{
			// omit throwing exception for duplicate
			if( $e->errorInfo[0] != '23000' )
			{
				throw new CDbException($e);
			}
			
			// TODO: write this error handling
			if( defined('YII_DEBUG') && YII_DEBUG==true)
			{
				//throw new CDbException($e);
			}
		}
		
		if( $result == 1 )
		{
			return array(
				'category'=>$category,
				'name'=>$name,
				'value'=>$val, // return original value (not casted to string or serialized)
				'type'=>$type,
			);
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * for param types see insert function description
	 * updates row in database
	 * optionally creates new entry, if row does not exist.
	 * 
	 * if $type equals to null it will be omitted in update
	 * @param type $category
	 * @param type $name
	 * @param type $value
	 * @param type $type
	 * @param type $insert if insert function should be called when setting does not exist
	 * @throws CException 
	 * 
	 * @return returns array with old and new value and type on success or false on fail or error
	 */
	public function update( $category, $name, $value, $type = null, $insert = false )
	{
		$tableName = $this->tableName;
		// store it temporary
		$val = $value;
	
		if( !$this->_db->tableExist($tableName) )
			throw new CException(Yii::t('yii','KSettings component is not installed.'));
		
		$update = array();
		
		// select old values from database
		$db = $this->getDbConnection();
		$old_value = $db->createCommand()
				->select('value, type')
				->from($tableName)
				->where('`category`=:c AND `name`=:n', array(':c'=>$category, ':n'=>$name))
				->queryRow();
		
		if( $old_value != false )
		{
			// validate params
			
			// if type equals to null use old type
			if( $type === null )
				$type = $old_value['type'];
			
			// validate new (or old) type
			if( !( $type == 'string'
				|| $type == 'int'
				|| $type == 'float'
				|| $type == 'boolean'
				|| $type == 'serialize' ) )
			{
				throw new CHttpException(409, 'KSettings component -> update: wrong type param value');
				return false;
			}

			// prepare value
			switch ($type)
			{
				case 'string':
					if( !is_string($value) )
						throw new CHttpException(409, 'KSettings component -> update: wrong value param value for string type');
					$value = (string)$value;
					break;
					
				case 'int':
					// check if it really is int
					if( !is_int($value) )
						throw new CHttpException(409, 'KSettings component -> update: wrong value param value for int type');
					$value = (string)$value;
					break;
					
				case 'float':
					if( !(is_float($value) || is_int($value)) )
						throw new CHttpException(409, 'KSettings component -> update: wrong value param value for float type');
					$value = (string)$value;
					break;
				
				case 'boolean':
					if( !is_bool($value) )
						throw new CHttpException(409, 'KSettings component -> update: wrong value param value for boolean type');
					$value = $value ? '1' : '0';
					break;
					
				case 'serialize':
					// TODO: ?allow to serialize everything?
					$value = serialize($value);
					break;
				
				default:
					throw new CHttpException(409, 'KSettings component -> update: wrong type param value');
					break;
			}

			$update['value'] = $value;
			$update['type'] = $type;
			
			
			// try to update
			try
			{
				// if old value and type equals to new omitt query and return
				if( !( $old_value['value'] === $value && $old_value['type'] === $type ) )
					$result = $db->createCommand()
						->update($tableName, $update, '`category`=:c AND `name`=:n', array(':c'=>$category, ':n'=>$name));
				else
					$result = 1;
				
				if($result == 1)
				{ // success
					// unserialize old one
					$old = $this->castValue($old_value['value'], $old_value['type']);
					if( $old == false )
						return false;
					
					return array(
						'category' => $category,
						'name' => $name,
						'old_value' => $old['value'], // return caseted value (unserialized)
						'old_type' => $old_value['type'],
						'value' => $val, // return original value
						'type' => $type,
					);
				}
				else
				{ 
//					if($insert)
//					{
//						return $this->insert($category, $name, $value, $type);
//					}
					return false;
				}
			}
			catch (CDbException $e)
			{
				// TODO: write this error handling
				// if any exception
				throw $e;
			}
		}
		else
		{
			if($insert)
			{
				if($type === null)
					$type = 'serialize';
				return $this->insert($category, $name, $value, $type);
			}
			return false;
		}
		return false;
	}
	
	
	
	/**
	 *
	 * @param type $category
	 * @param type $name
	 * @return type array on success or false on fail
	 */
	public function get( $category, $name = null )
	{
		$db = $this->getDbConnection();
		
		// get from database
		if( $name !== null )
			$result = $db->createCommand()
					->select('*')
					->from($this->tableName)
					->where('`category`=:c AND `name`=:n', array(':c'=>$category, ':n'=>$name))
					->queryRow();
		else
			$result = $db->createCommand()
					->select('*')
					->from($this->tableName)
					->where('`category`=:c', array(':c'=>$category))
					->queryAll();
		
		// if not found
		if( $result == false )
			return false;
		
		// perform type casting
		if( $name !== null )
		{ // only one result
			$value = $this->castValue($result['value'], $result['type']);
			if( $value == false )
				return false;
			
			return array(
				'category' => $category,
				'name' => $name,
				'value' => $value['value'],
				'type' => $result['type'],
			);
		}
		else
		{
			foreach( $result as $key => $setting )
			{
				// cast
				$value = $this->castValue($setting['value'], $setting['type']);
				// if any one can't be casted return false
				if( $value == false )
					return false;
				
				// assign
				$result[$key]['value'] = $value['value'];
			}
			return $result;
		}
		return false;
	}
	
	
	
	/**
	 * returns only value of single setting
	 * throws exception on any error
	 * throws exception if setting is not found
	 * @param type $category
	 * @param type $name 
	 */
	public function getValue( $category, $name )
	{
		$setting = $this->get( $category, $name );
		
		if( $setting == false )
			throw new CHttpException(404, 'KSettings component: setting not found in database');
		
		return $setting['value'];
	}
	
	
	
	/**
	 *
	 * @param type $category
	 * @param type $name
	 * @return type 
	 */
	public function delete( $category, $name = null )
	{
		$db = $this->getDbConnection();
		
		if( $name !== null )
			return $db->createCommand()
					->delete($this->tableName, '`category`=:c AND `name`=:n', array(':c'=>$category, ':n'=>$name));
		else
			return $db->createCommand()
					->delete($this->tableName, '`category`=:c', array(':c'=>$category));
	}
	
	
	
	/**
	 * casts value from string or serialized to specific type
	 * @param type $value
	 * @param type $type 
	 * 
	 * @return array with casted value and type or false on fail
	 */
	private function castValue( $value, $type )
	{
		switch( $type )
		{
			case 'string':
				if( ((string)$value) == $value )
				{
					return array(
						'value' => (string)$value,
						'type' => $type,
					);
				}
				break;
				
			case 'int':
				if( ((int)$value) == $value )
				{
					return array(
						'value' => (int)$value,
						'type' => $type,
					);
				}
				break;
				
			case 'float':
				if( ((float)$value) == $value )
				{
					return array(
						'value' => (float)$value,
						'type' => $type,
					);
				}
				break;
				
			case 'boolean':
				if( ((boolean)$value) == $value )
				{
					return array(
						'value' => (boolean)$value,
						'type' => $type,
					);
				}
				break;
				
			case 'serialize':
				return array(
					'value' => unserialize($value),
					'type' => $type,
				);
		}
		return false;
	}
	
}