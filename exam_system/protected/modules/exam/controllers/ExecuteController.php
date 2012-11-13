<?php

class ExecuteController extends KPublicController
{
	public $testModel = null;
	public $userLogModel = null;
	public $questionSet = null;

	const ERROR = -100;
	const PERFORM = 1;
	const NOT_STARTED = -1;
	const EXPIRED = -2;
	const WRONG_STATUS = -3;
	
	const USER_ACCESS = 2;
	const USER_ACCESS_DENIDED = -4;
	
	public function actionIndex()
	{
		if((isset($_POST['execute-exam']) || isset($_POST['continue-exam'])) && isset($_POST['exam_id'])) {
			
			$this->testModel = Test::model()->findByPk($_POST['exam_id']);
			
			// check if test can be performed
			if($this->testCanBePerformed() != self::PERFORM){
				Yii::app()->user->setFlash('error', 'Nie ma takiego testu');
				$this->redirect('/');
			}
			
			// check if user has acces to test (is in test group)
			if($this->checkAccess() != self::USER_ACCESS){
				Yii::app()->user->setFlash('error', 'Brak dostępu');
				$this->redirect('/');
			}
			
			// find user log
			$this->userLogModel = TestUserLog::model()->findByAttributes(array(
				'test_id' => $this->testModel->id,
				'user_id' => Yii::app()->user->id,
			));
			
			// get question set
			$this->questionSet = QuestionSet::getHistoryByIdVersion($this->testModel->question_set_id, $this->testModel->question_set_version);
			
			if($this->userLogModel == null) { // first time
				$this->prepare();
			} elseif($this->userLogModel->updateStatus()) {
				if($this->userLogModel->status == TestUserLog::STATUS_STARTED) {
					$this->render();
				} elseif($this->userLogModel->status == TestUserLog::STATUS_COMPLETED) {
					echo 'completed';
				} elseif($this->userLogModel->status == TestUserLog::STATUS_CANCELED) {
					echo 'canceled';
				}
			}
			exit;
		} else {
			$this->redirect('/');
		}
	}
	
	public function prepare() {
		$time = time();
		$this->userLogModel = new TestUserLog();
		$this->userLogModel->user_id = Yii::app()->user->id;
		$this->userLogModel->test_id = $this->testModel->id;
		$this->userLogModel->status = TestUserLog::STATUS_STARTED;
		$this->userLogModel->create_date = $time;
		$this->userLogModel->end_date = $time + $this->testModel->duration_time;
		$this->userLogModel->last_change_date = $time;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->userLogModel->save();
			
			$this->generateQuestions();
			
			$transaction->commit();
			$this->render();
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderError();
		}
	}
	
	public function render() {
		$this->render('index');
	}
	
	public function renderError() {
		echo 'error';
	}
	
	public function checkAccess() {
		if($this->testModel==null)
			return self::ERROR;
		
		$data = $this->testModel->searchForUser();
		foreach($data->data as $test) {
			if($test->id == $this->testModel->id) {
				return self::USER_ACCESS;
			}
		}
		return self::USER_ACCESS_DENIDED;
	}
	
	public function testCanBePerformed() {
		if($this->testModel==null)
			return self::ERROR;
		
		if($this->testModel->status != Test::STATUS_CONFIRMED)
			return self::WRONG_STATUS;
		
		if($this->testModel->begin_time > time())
			return self::NOT_STARTED;
		
		if(($this->testModel->end_time + $this->testModel->duration_time) < time())
			return self::EXPIRED;
		
		return self::PERFORM;
	}
	
	public function generateQuestions() {
		foreach($this->testModel->testQuestionGroups as $questionGroup) {
			if($questionGroup->question_quantity > 0 ) {
				foreach($this->questionSet->questionGroups as $group) {
					if($questionGroup->group_id == $group->id) {
						$questions = $group->getCorrectQuestions($questionGroup->question_types);
						KDump::d($questions);
						break;
					}
				}
			}
		}
		die;
	}
}