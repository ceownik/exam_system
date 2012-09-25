<?php

class AdminController extends KAdminController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// login action displays login form and performes user login for administration panel
			'login' => array(
				'class' => 'application.controllers.actions.LoginAction',
				'layout' => '//layouts/login_page',
				'returnUrl' => array('admin/index'),
				'css' => array(
					Yii::app()->request->baseUrl.'/themes/admin/css/login_page.css',
				),
				'js' => array(
					Yii::app()->request->baseUrl.'/themes/admin/js/login_page.js',
				),
			),
			// logout action
			'logout' => array(
				'class' => 'application.controllers.actions.LogoutAction',
				'returnUrl' => array('admin/login'),
			),
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
			array( 'allow', // allow guest user to...
				'actions' => array( 'login', 'logout' ),
				'users' => array( '*' ),
			),
			array( 'allow', // allow authenticated user to...
				'actions' => array(  ),
				'users' => array( '@' ),
			),
			array( 'deny', // deny all users
				'users' => array( '*' ),
			),
		);

		return $rules;
	}
	
	
	
	public function init()
	{
		parent::init();
		$this->headerTitle = 'Welcome to KCMS';
	}
	
	
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
	
}