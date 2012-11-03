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
	
	public function beforeAction($action) {
		parent::beforeAction($action);
		if($action->id!='index') {
			$this->module->menuItems = array(
				'list' => array(
					'label'=>'View Sets List', 
					'url'=>array('/admin/question/index'), 
					'linkOptions' => array('class'=>'',)
				),
			);
		}
		return true;
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
		}
		$this->render('create-question-set',array(
			'model'=>$model,
			'cancelUrl'=>'index',
		));
	}
	
	public function actionUpdateQuestionSet($id, $type=0) {
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
					if($type == 1) {
						$this->redirect(array('/admin/question/viewQuestionSet/id/'.$model->primaryKey));
					} else {
						$this->redirect(array('/admin/question/index'));
					}
				}
			}
		}
		
		$cancelUrl = 'index';
		if($type==1) {
			$cancelUrl = '/admin/question/viewQuestionSet/id/'.$model->primaryKey;
		}
		
		$this->render('create-question-set',array(
			'model'=>$model,
			'cancelUrl'=>$cancelUrl,
		));
	}
	
	public function actionViewQuestionSet($id) {
		
		$model = $this->getQuestionSet($id);
		
		$this->headerTitle = 'Question set: ' . $model->name;
		
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
	
	private function getQuestionGroup($id) {
		$model = QuestionGroup::model()->findByPk($id);
		if($model==null) {
			KThrowException::throw404();
			exit;
		}
		return $model;
	}
	
	public function actionCreateQuestionGroup($set_id) {
		$this->headerTitle = 'Create question group';
		
		$setModel = $this->getQuestionSet($set_id);
		$groupModel = new QuestionGroup;
		
		$groupModel->set_id = $set_id;
		
		if(isset($_POST['QuestionGroup'])) {
			$groupModel->attributes = $_POST['QuestionGroup'];
			
			if($groupModel->validate())
			{
				if($groupModel->save())
				{
					Yii::app()->user->setFlash('success', "Item created successfully.");
					$this->redirect(array('/admin/question/viewQuestionSet/id/'.$set_id));
				}
			}
		}
		$this->render('create-question-group', array('model'=>$groupModel));
	}
	
	public function actionUpdateQuestionGroup($id, $type=0) {
		$this->headerTitle = 'Update question group';
		
		$model = $this->getQuestionGroup($id);
		
		if(isset($_POST['QuestionGroup']))
		{
			$model->attributes = $_POST['QuestionGroup'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item changed successfully.");
					$this->redirect(array('/admin/question/viewQuestionSet/id/'.$model->set_id));
				}
			}
		}
		$this->render('create-question-group',array(
			'model'=>$model,
		));
	}
}