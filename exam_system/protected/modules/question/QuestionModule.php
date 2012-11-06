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
	}

	public function beforeControllerAction($controller, $action)
	{
		$this->menuItems = array(
			'create' => array(
				'label'=>'Create question set', 
				'url'=>array('/admin/question/createQuestionSet'), 
				'linkOptions' => array('class'=>'',)
			),
		);
		
		$this->moduleTitle = 'Questions';
		
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
