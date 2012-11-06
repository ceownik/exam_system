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
			array('deny',
				'users'=>array('*')
				)
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
		$this->headerTitle = 'Question Sets';
		
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
		
		$this->headerTitle = 'Question set';
		
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
	
	private function getQuestion($id) {
		$model = Question::model()->findByPk($id);
		if($model==null) {
			KThrowException::throw404();
			exit;
		}
		return $model;
	}
	
	private function getAnswer($id) {
		$model = Answer::model()->findByPk($id);
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
	
	public function actionRemoveQuestionGroup($id) {
		$group = $this->getQuestionGroup($id);
		$group->is_deleted = true;
		$group->save();
		
		$this->redirect(array('admin/viewQuestionSet/id/'.$group->set_id));
	}
	
	public function actionCreateQuestion($group_id) {
		$this->headerTitle = 'Create question';
		
		$model = new Question;
		$group = $this->getQuestionGroup($group_id);
		
		$model->group_id = $group->primaryKey;
		
		if(isset($_POST['Question'])) {
			$model->attributes = $_POST['Question'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item created successfully.");
					$this->redirect(array('/admin/question/viewQuestionSet/id/'.$group->set_id));
				}
			}
		}
		$this->render('create-question', array(
			'model'=>$model,
			'group'=>$group,
			));
	}
	
	public function actionViewQuestion($id) {
		$this->headerTitle = 'Details of question';
		
		$model = $this->getQuestion($id);
		
		$this->render('view-question',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdateQuestion($id) {
		$this->headerTitle = 'Update question';
		
		$model = $this->getQuestion($id);
		
		if(isset($_POST['Question']))
		{
			$model->attributes = $_POST['Question'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item changed successfully.");
					$this->redirect(array('/admin/question/viewQuestionSet/id/'.$model->group->set_id));
				}
			}
		}
		$this->render('update-question',array(
			'model'=>$model,
		));
	}
	
	public function actionRemoveQuestion($id) {
		$question = $this->getQuestion($id);
		$question->is_deleted = 1;
		$question->save();
		
		$this->redirect(array('admin/viewQuestionSet/id/'.$question->group->set_id));
	}
	
	public function actionAddAnswer($id) {
		$this->headerTitle = 'Add answer';
		
		$question = $this->getQuestion($id);
		
		if($question->type == Question::TYPE_MCSA) {
			$model = new Answer;
			$model->question_id = $question->primaryKey;
			
			if(isset($_POST['Answer'])) {
				$model->attributes = $_POST['Answer'];

				if($model->validate())
				{
					if($model->save())
					{
						Yii::app()->user->setFlash('success', "Item created successfully.");
						$this->redirect(array('/admin/question/viewQuestionSet/id/'.$question->group->set_id));
					}
				}
			}
			$this->render('create-mcsa-answer', array(
				'model'=>$model,
				'question'=>$question,
				));
		}
	}
	
	public function actionUpdateAnswer($id) {
		$this->headerTitle = 'Update answer';
		
		$answer = $this->getAnswer($id);
		
		if($answer->question->type == Question::TYPE_MCSA) {
			if(isset($_POST['Answer'])) {
				$answer->attributes = $_POST['Answer'];
				if($answer->validate()) {
					if($answer->save()) {
						$this->redirect(array('admin/viewQuestionSet/id/'.$answer->question->group->set_id));
					}
				}
			}
			
			$this->render('update-mcsa-answer', array(
				'model' => $answer,
			));
		}
	}
	
	public function actionRemoveAnswer($id) {
		$answer = $this->getAnswer($id);
		$answer->is_deleted = 1;
		$set_id = $answer->question->group->set_id;
		$answer->save();
		
		$this->redirect(array('admin/viewQuestionSet/id/'.$set_id));
	}
}