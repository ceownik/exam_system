<?php

class ExamModule extends KModule
{
	public function init()
	{
		parent::init();
		
		// import the module-level models and components
		$this->setImport(array(
			'exam.models.*',
			'exam.components.*',
		));
		
		$this->moduleTitle = 'Users';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
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
