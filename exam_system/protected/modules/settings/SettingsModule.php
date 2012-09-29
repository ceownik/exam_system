<?php

/**
 * 
 */

/**
 * 
 */
class SettingsModule extends KModule {
	
	/**
	 * configurable component name
	 */
	public $componentName;
			
			
			
	/**
	 * link to settings component
	 */
	private $component;
	public function getComponentInstance()
	{
		return $this->component;
	}
	
	

	/**
	 * this method is called when the module is being created you may place code
	 * here to customize the module or the application
	 */
	public function init()
	{
		parent::init();
		
		$this->moduleTitle = 'Settings';
		
		$this->component = Yii::app()->{$this->componentName};
	}

	// SETTERS & GETTERS


	/**
	 * this method is called before any module controller action is performed
	 * you may place customized code here
	 * @param CController $controller
	 * @param CAction $action
	 * @return boolean
	 */
	public function beforeControllerAction( $controller, $action )
	{
		$this->menuItems = array(
			
		);
		
		
		if( parent::beforeControllerAction( $controller, $action ) )
		{
			$controller->headerTitle = $this->moduleTitle;
			return true;
		}
		else
			return false;
	}

	
	
	/**
	 * 
	 */
	public $menuItems; 

}