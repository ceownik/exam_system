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
				'roles' => array('exam')
			),
			array( 'deny', // deny all users
				'users' => array( '*' ),
			),
		);

		return $rules;
	}
	
	public function getTestModel($id) {
		$model = Test::model()->findByPk($id);
		if($model==null) {
			KThrowException::throw404();
			exit;
		}
		return $model;
	}
	
	private function getQuestionSet($id) {
		$model = QuestionSet::model()->findByPk($id);
		if($model==null) {
			KThrowException::throw404();
			exit;
		}
		return $model;
	}
	
	public function actionIndex() {
		$this->headerTitle = 'Exams';
		
		$this->module->menuItems[] = array(
			'label'=>'Create', 
			'url'=>array('/admin/exam/create'), 
			'linkOptions' => array('class'=>'create '),
		);
		
		$model = new Test();
		$model->unsetAttributes();
		if(isset($_GET['Test']))
			$model->attributes = $_GET['Test'];
		
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionCreate() {
		$this->headerTitle = 'Create exam';
		$this->module->menuItems[] = array(
			'label'=>'View list', 
			'url'=>array('/admin/exam/index')
		);
		
		$model = new Test();
		
		if(isset($_POST['Test'])) {
			$model->attributes = $_POST['Test'];
			$model->userGroups = $model->groupsIds;
			$model->question_set_id = $_POST['Test']['question_set_id'];
			$questionSet = $this->getQuestionSet($model->question_set_id);
			$model->question_set_version = $questionSet->last_update_date;
			$model->status = Test::STATUS_NEW;
			
			if($model->validate()) {
				$transaction = Yii::app()->db->beginTransaction();
				try {
					$model->save();
					$model->updateUserGroups($_POST['Test']['groupsIds']);
					$transaction->commit();
					$this->redirect(array('/admin/configure/id/'.$model->primaryKey));
				} catch(Exception $e) {
					$transaction->rollback();
					Yii::app()->user->setFlash('error', 'Transaction unsuccessful');
					$this->refresh();
				}
			}
		}
		
		$this->render('create-test',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdate($id) {
		$this->headerTitle = 'Configure exam';
		$this->module->menuItems[] = array(
			'label'=>'Create', 
			'url'=>array('/admin/exam/create'), 
			'linkOptions' => array('class'=>'create '),
		);
		$this->module->menuItems[] = array(
			'label'=>'View list', 
			'url'=>array('/admin/exam/index')
		);
		
		$model = $this->getTestModel($id);
		$oldQuestionSet = $model->question_set_id;
		
		if(isset($_POST['Test'])) {
			$model->attributes = $_POST['Test'];
			$model->userGroups = $model->groupsIds;
			$model->question_set_id = $_POST['Test']['question_set_id'];
			
			if($model->validate()) {
				$transaction = Yii::app()->db->beginTransaction();
				try {
					$model->updateUserGroups($_POST['Test']['groupsIds']);
					if($model->question_set_id!=$oldQuestionSet) { // question set changed
						$model->updateQuestionGroups($model->question_set_id);
						$questionSet = $this->getQuestionSet($model->question_set_id);
						$model->question_set_version = $questionSet->last_update_date;
						$model->status = Test::STATUS_NEW;
					}
					$model->save();
					$transaction->commit();
					$this->redirect(array('/admin/exam/index'));
				} catch(Exception $e) {
					$transaction->rollback();
					Yii::app()->user->setFlash('error', 'Transaction unsuccessful');
					$this->refresh();
				}
			}
		}
		
		$this->render('create-test',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdateQuestionGroups($id, $r=0) {
		$model = $this->getTestModel($id);
		
		$url = '/admin/exam/index';
		if($r==1)
			$url = '/admin/exam/configure/id/'.$model->id;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$model->updateQuestionGroups($model->question_set_id);
			$questionSet = $this->getQuestionSet($model->question_set_id);
			$model->question_set_version = $questionSet->last_update_date;
			$model->status = Test::STATUS_NEW;

			$model->save();
			$transaction->commit();
			$this->redirect(array($url));
		} catch(Exception $e) {
			$transaction->rollback();
			Yii::app()->user->setFlash('error', 'Transaction unsuccessful');
			$this->redirect(array($url));
		}
	}
	
	public function actionConfigure($id) {
		$this->headerTitle = 'Configure exam';
		$this->module->menuItems[] = array(
			'label'=>'Create', 
			'url'=>array('/admin/exam/create'), 
			'linkOptions' => array('class'=>'create '),
		);
		$this->module->menuItems[] = array(
			'label'=>'View list', 
			'url'=>array('/admin/exam/index')
		);
		
		$model = $this->getTestModel($id);
		//$questionSet = $this->getQuestionSet($model->question_set_id);
		if($model->question_set_version != $model->questionSet->last_update_date) {
			Yii::app()->user->setFlash('warning', 'Zestaw pytań z którego utworzony jest egzamin uległ zmianie! '.CHtml::link('(Uaktualnij)', array('/admin/exam/updateQuestionGroups/id/'.$model->id.'/r/1')).' (kliknięcie w link spowoduje zresetowanie ustawień egzaminu)');
		}
		
		if(isset($_POST['TestQuestionGroup'])) {
			
			$transaction = Yii::app()->db->beginTransaction();
				try {
					foreach($_POST['TestQuestionGroup'] as $id=>$settings) {
						$testQuestionGroup = TestQuestionGroup::model()->findByAttributes(array(
							'test_id'=>$model->id,
							'group_id'=>$id,
						));
						if($testQuestionGroup==null)
							throw new Exception;
						$testQuestionGroup->attributes = $settings;
						$testQuestionGroup->save();
					}
					$model->status = Test::STATUS_PREPARED;
					$model->save();
					
					$transaction->commit();
					Yii::app()->user->setFlash('success', 'Item updated successfully');
					$this->redirect(array('/admin/exam/index/'));
				} catch(Exception $e) {
					$transaction->rollback();
					Yii::app()->user->setFlash('error', 'Transaction unsuccessful');
				}
		}
		
		$this->render('configure-test',array(
			'model'=>$model,
			//'questionSet'=>$questionSet,
		));
	}
	
	public function actionConfirmExam($id, $return=0) {
		$model = $this->getTestModel($id);
		
		$url = '/admin/exam/index';
		if($return==1) 
			$url = '/admin/exam/configure/id/'.$id;
		
		if($model->status != Test::STATUS_PREPARED) {
			Yii::app()->user->setFlash('error', 'Egzamin nie został skonfigurowany');
			$this->redirect($url);
		} else {
			$model->status = Test::STATUS_CONFIRMED;
			if($model->save()) {
				$this->redirect($url);
			}
			Yii::app()->user->setFlash('error', 'Egzamin nie został poprawnie zatwierdzony');
			$this->redirect($url);
		}
	}
	
	public function actionGetQuestionCount() {
		if(Yii::app()->request->isAjaxRequest) {
			if(isset($_POST['testId']) && isset($_POST['groupId']) && isset($_POST['type'])) {
				
				$test = $this->getTestModel($_POST['testId']);
				
				$history = $test->questionSet->getHistoryByVersion($test->question_set_version);
				$group = null;
				foreach($history->questionGroups as $g)
					if($g->id==$_POST['groupId']) {
						$group = $g;
						break;
					}
				if($group!=null) {
					echo CJSON::encode(array(
						'status'=>'render',
						'html'=>'success',
						'count'=>$group->getCorrectQuestionsCount($_POST['type']),
					));
					exit;
				}	
			}
			echo CJSON::encode(array(
				'status'=>'error',
				'html'=>'error',
			));
			exit;
		}
	}
}