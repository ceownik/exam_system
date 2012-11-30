<?php

class SiteController extends KPublicController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
			// login action displays login form and performes user login
			'login' => array(
				'class' => 'application.controllers.actions.LoginAction',
			),
			// logout
			'logout' => array(
				'class' => 'application.controllers.actions.LogoutAction',
			),
		);
	}
	
	
	
	public function filters()
	{
		return array(
			// perform access control for CRUD operations (basic access control)
			// by default deny all users override accessRules method to change
			// this
			'accessControl',
		);
	}
	
	
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$rules = array(
			array( 'allow', // allow guest user to...
				'users' => array( '*' ),
			),
			array('deny'),
		);

		return $rules;
	}
	
	
	
	public function actionIndex()
	{
		if(Yii::app()->user->isGuest) 
			$this->redirect(array('login'));
		
		Yii::import('exam.models.*');
		
		$model = new Test();
		$model->unsetAttributes();
		if(isset($_GET['Test']))
			$model->attributes = $_GET['Test'];
		
//		if(isset($_POST['execute-exam'])) {
//			$this->redirect('/exam/execute');
//		}

		$this->render('index', array(
			'model'=>$model,
		));
	}
	
	
	
	public function actionHome() {
		$this->render('home', array(
			
		));
	}
	
	
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
	
	
	
	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
}