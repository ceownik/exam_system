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
			'questionSet' => array(
				'label'=>'Question set',
				'visible'=>Yii::app()->user->checkAccess('question'),
				'linkOptions'=>array(
					'class'=>'parent'
				), 
				'items'=>array(
					'list' => array(
						'label'=>'Show list', 
						'url'=>array('/admin/question/index'), 
						'linkOptions' => array('class'=>'',)
					),
					'create' => array(
						'label'=>'Create', 
						'url'=>array('/admin/question/createQuestionSet'), 
						'linkOptions' => array('class'=>'',)
					),
				)
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
