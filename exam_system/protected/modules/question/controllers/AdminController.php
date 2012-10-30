<?php

class AdminController extends KAdminController
{
	public $rbacOperations;
	
	
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
//			'index' => array(
//				'class' => 'application.modules.users.controllers.actions.IndexAction',
//			),
		);
	}
	
	
	
	/**
	 * Specifies the access control rules.
	 * 
	 * some actions in this controller are public actions
	 */
	public function accessRules()
	{
		$rules = array(
			array( 'allow', // allow authenticated user to...
				'actions' => array( 'index' ),
				'roles' => array('question')
			),
		);

		return $rules;
	}
	
	public function actionIndex() 
	{
		$this->headerTitle = 'Questions';
		
		$model = new QuestionSet;
		$model->unsetAttributes();
		if(isset($_GET['QuestionSet']))
			$model->attributes = $_GET['QuestionSet'];
		
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionCreateQuestionSet() {
		$this->headerTitle = 'Create question set';
		
		$model = new QuestionSet;
		$model->setScenario('create');
		
		if(isset($_POST['QuestionSet']))
		{
			$model->attributes = $_POST['QuestionSet'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item created successfully.");
					$this->redirect(array('/admin/question/index'));
				}
			}
			else
			{
				
			}
		}
		
		$this->render('create-question-set',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdateQuestionSet($id) {
		$this->headerTitle = 'Update question set';
		
		$model = $this->getQuestionSet($id);
		
		if(isset($_POST['QuestionSet']))
		{
			$model->attributes = $_POST['QuestionSet'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item changed successfully.");
					$this->redirect(array('/admin/question/index'));
				}
			}
			else
			{
				
			}
		}
		
		$this->render('create-question-set',array(
			'model'=>$model,
		));
	}
	
	public function actionViewQuestionSet($id) {
		$model = $this->getQuestionSet($id);
		
		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	private function getQuestionSet($id) {
		$model = QuestionSet::model()->findByPk($id);
		
		if($model==null) {
			KThrowException::throw404();
			exit;
		}
		
		return $model;
	}
}