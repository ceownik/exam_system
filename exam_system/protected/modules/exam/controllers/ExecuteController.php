<?php

class ExecuteController extends KPublicController
{
	public function actionIndex()
	{
		if((isset($_POST['execute-exam']) || isset($_POST['continue-exam'])) && isset($_POST['exam_id'])) {
			
			$testModel = Test::model()->findByPk($_POST['exam_id']);
			
			$this->checkAccess($testModel->id);
			
			if(isset($_POST['execute-exam'])) {
				$this->prepare();
			} elseif(isset($_POST['continue-exam'])) {
				$this->render();
			}
		} else {
			$this->redirect('/');
		}
	}
	
	public function prepare() {
		$this->render('index');
	}
	
	public function checkAccess($testId) {
		$model = new Test();
		$model->id = $testId;
		$data = $model->searchForUser();
		KDump::d($data->itemCount);
	}
}