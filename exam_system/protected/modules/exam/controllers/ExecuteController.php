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
	const FINISHED = -4;
	
	const USER_ACCESS = 2;
	const USER_ACCESS_DENIDED = -4;
	
	public function actionIndex()
	{	
		if((isset($_POST['execute-exam']) || isset($_POST['continue-exam'])) && isset($_POST['exam_id']) && (!Yii::app()->user->isGuest)) {
			
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
					$this->renderHtml();
				} elseif($this->userLogModel->status == TestUserLog::STATUS_COMPLETED) {
					Yii::app()->user->setFlash('alert', 'Test został zakończony');
					$this->redirect('/');
				} elseif($this->userLogModel->status == TestUserLog::STATUS_CANCELED) {
					Yii::app()->user->setFlash('alert', 'Test został anulowany');
					$this->redirect('/');
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
		$this->userLogModel->end_date = $time + ($this->testModel->duration_time*60);
		$this->userLogModel->last_change_date = $time;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->userLogModel->save();
			
			$this->generateQuestions();
			
			$transaction->commit();
			$this->renderHtml();
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderError();
		}
	}
	
	public function renderHtml() {
		$this->render('index', array(
			'testUserLog'=>$this->userLogModel,
			'questionSet'=>$this->questionSet,
		));
	}
	
	public function renderError() {
		throw new HttpException('Error');
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
		
		if($this->testModel->status==Test::STATUS_FINISHED)
			return self::FINISHED;
		
		if($this->testModel->status != Test::STATUS_CONFIRMED)
			return self::WRONG_STATUS;
		
		if($this->testModel->begin_time > time())
			return self::NOT_STARTED;
		
		if(($this->testModel->end_time + $this->testModel->duration_time) < time())
			return self::EXPIRED;
		
		return self::PERFORM;
	}
	
	public function generateQuestions() {
		$selectedQuestions = array();
		foreach($this->testModel->testQuestionGroups as $questionGroupSettings) {
			if($questionGroupSettings->question_quantity > 0 ) {
				foreach($this->questionSet->questionGroups as $group) { // find correct group
					if($questionGroupSettings->group_id == $group->id) {
						$questions = $group->getCorrectQuestions($questionGroupSettings->question_types);
						if(count($questions) < $questionGroupSettings->question_quantity) {
							KThrowException::throw404();
						} else {
							$selected = array_rand($questions, $questionGroupSettings->question_quantity);
							if(!is_array($selected))
								$selected = array($selected);
							
							foreach($selected as $q) {
								$question = $questions[$q];
								$selectedQuestions[] = $question;
							}
						}
						break;
					}
				}
			}
		}
		shuffle($selectedQuestions);
		foreach($selectedQuestions as $question) {
			$questionLog = new TestUserQuestionLog();
			$questionLog->test_user_id = $this->userLogModel->id;
			$questionLog->question_id = $question->id;
			$questionLog->save();
			if($question->type==Question::TYPE_MCSA || $question->type==Question::TYPE_MCMA) {
				$this->generateAnswers($question, $questionGroupSettings->answers, $questionLog);
			}
		}
	}
	
	public function generateAnswers($question, $count, $questionLog) {
		$answers = array();
		$correctAnswers = array();
		$wrongAnswers = array();
		if($question->type==Question::TYPE_MCSA) {
			foreach($question->answers as $k=>$answer) {
				if($answer->is_correct) {
					$correctAnswers[$k] = $k;
				} else {
					$wrongAnswers[$k] = $k;
				}
			}
			$selectedCorrect = array(array_rand($correctAnswers));
			if(!is_array($selectedCorrect))
				$selectedCorrect = array($selectedCorrect);
			$selectedWrong = array_rand($wrongAnswers, $count - 1);
			if(!is_array($selectedWrong))
				$selectedWrong = array($selectedWrong);
		} elseif($question->type==Question::TYPE_MCMA) {
			foreach($question->answers as $k=>$answer) {
				if($answer->is_correct) {
					$correctAnswers[$k] = $k;
				} else {
					$wrongAnswers[$k] = $k;
				}
			}
			$selectedCorrect = array_rand($correctAnswers); // pick one correct
			unset($correctAnswers[$selectedCorrect]); // remove it from rest of correct answers
			if(!is_array($selectedCorrect))
				$selectedCorrect = array($selectedCorrect);
			$allAnswers = CMap::mergeArray($correctAnswers, $wrongAnswers);
			$selectedWrong = array_rand($allAnswers, $count - 1);
			if(!is_array($selectedWrong))
				$selectedWrong = array($selectedWrong);
		}
		foreach($selectedCorrect as $correct)
			$answers[] = $question->answers[$correct];
		foreach($selectedWrong as $wrong)
			$answers[] = $question->answers[$wrong];
		shuffle($answers);
		$i = 0;
		foreach($answers as $answer) {
			$model = new TestUserAnswerLog();
			$model->test_log_id = $questionLog->id;
			$model->answer_id = $answer->id;
			$model->display_order = ++$i;
			$model->save();
		}
	}
	
	public function actionSubmitTest() {
		if(Yii::app()->request->isAjaxRequest) {
			
			
			$this->userLogModel = TestUserLog::model()->findByPk($_POST['id']);
			$this->testModel = $this->userLogModel->test;
			
			$status = $this->testCanBePerformed();
			if($status==self::EXPIRED) {
				echo CJSON::encode(array(
					'status'=>'end',
					'msg'=>'Czas na wykonanie testu dobiegł końca',
				));
				exit(0);
			}
			if($status==self::FINISHED) {
				echo CJSON::encode(array(
					'status'=>'error',
					'msg'=>'Test został zakończony przez administratora',
				));
				exit(0);
			}
			if($status!=self::PERFORM) {
				echo CJSON::encode(array(
					'status'=>'error',
					'msg'=>'Wystąpił nieoczekiwany błąd',
					's'=>$status,
				));
				exit(0);
			}
			
			$post = array();
			parse_str($_POST['test'], $post);
			$end = $_POST['endTest']=='true'? true : false;
			
			$transaction = Yii::app()->db->beginTransaction();
			try {
				$this->userLogModel->updateStatus();
				if($this->userLogModel->status == TestUserLog::STATUS_STARTED) {
					$time = time();
					foreach($this->userLogModel->testUserQuestionLogs as $questionLog) {
						$questionLog->last_change_date = $time;
						$questionLog->save();
						if($questionLog->question->type==Question::TYPE_MCSA) {
							$selected = null;
							if(isset($post['question-'.$questionLog->id])) {
								$selected = $post['question-'.$questionLog->id];
							}
							foreach($questionLog->testUserAnswerLogs as $answerLog) {
								if($selected==null) {
									$answerLog->selected = -1;
									$answerLog->last_change_date = $time;
								} else {
									if($answerLog->id==$selected) {
										$answerLog->selected = 1;
									} else {
										$answerLog->selected = 0;
									}
								}
								$answerLog->save();
							}
						} elseif($questionLog->question->type==Question::TYPE_MCMA) {
							$selected = null;
							if(isset($post['question-'.$questionLog->id])) {
								$selected = $post['question-'.$questionLog->id];
							}
							foreach($questionLog->testUserAnswerLogs as $answerLog) {
								if($selected==null) {
									$answerLog->selected = -1;
									$answerLog->last_change_date = $time;
								} else {
									if(in_array($answerLog->id, $selected)) {
										$answerLog->selected = 1;
									} else {
										$answerLog->selected = 0;
									}
								}
								$answerLog->save();
							}
						}
					}
					if($end==true) {
						$this->userLogModel->updateStatus(TestUserLog::STATUS_COMPLETED);
						//$this->userLogModel->status = TestUserLog::STATUS_COMPLETED;
						//$this->userLogModel->update(array('status');
					}
					$transaction->commit();
				} elseif($this->userLogModel->status == TestUserLog::STATUS_COMPLETED) {
					$transaction->commit();
					echo CJSON::encode(array(
						'status'=>'end',
						'msg'=>'Czas na wykonanie testu dobiegł końca.',
						'time_left'=>$this->userLogModel->end_date - time(),
					));
					exit(0);
				} elseif($this->userLogModel->status == TestUserLog::STATUS_CANCELED) {
					$transaction->commit();
					echo CJSON::encode(array(
						'status'=>'end',
						'msg'=>'Test został przerwany przez administratora',
					));
					exit(0);
				}
				
			} catch(Exception $e) {
				echo CJSON::encode(array(
					'status'=>'error',
					'msg'=>'Wystąpił nieoczekiwany błąd',
					's'=>'transaction',
					'e'=>$e->getMessage(),
					'file'=>$e->getFile(),
					'line'=>$e->getLine(),
				));
				exit(0);
			}
			
			echo CJSON::encode(array(
				'status'=>'success',
				'post'=>$post,
				'time_left'=>$this->userLogModel->end_date - time(),
				'end'=>$end==true,
			));
			exit(0);
		}
	}
}