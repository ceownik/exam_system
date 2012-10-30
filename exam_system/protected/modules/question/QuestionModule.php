<?php

class QuestionModule extends KModule
{
	public function init()
	{
		// import the module-level models and components
		$this->setImport(array(
			'question.models.*',
			'question.components.*',
		));
		
		$this->moduleTitle = 'Questions';
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
