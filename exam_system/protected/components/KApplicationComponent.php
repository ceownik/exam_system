<?php
/**
 * This ApplicationComponent class should be used for every component which uses
 * database connection.
 * This class provides basic functions and setters for component - database relations.
 * 
 */
class KApplicationComponent extends CApplicationComponent
{
	/**
	 * @var string the ID of the database connection application component. Defaults to 'db'.
	 */
	public $connectionID='db';
	
	
	public function init()
	{
		parent::init();
		
		if(defined('YII_DEBUG') && YII_DEBUG===true)
			$this->install();
	}
	
	
	/**
	 * install component database tables
	 * check for every table before install
	 * 
	 * @return boolean
	 */
	public function install()
	{
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
		return true;
	}
	
	
	
	protected $_db;

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
				throw new CException(Yii::t('yii','KApplicationComponent.connectionID is invalid. Please make sure "{id}" refers to a valid database application component.',
					array('{id}'=>$this->connectionID)));
		}
		return $this->_db;
	}
	
	
	
}