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
			'index' => array(
				'class' => 'application.modules.users.controllers.actions.IndexAction',
			),
			'create' => array(
				'class' => 'application.modules.users.controllers.actions.CreateAction',
				'css' => array(
					Yii::app()->request->baseUrl.'/themes/admin/css/jquery.ui.all.css',
				)
			),
			'view' => array(
				'class' => 'application.modules.users.controllers.actions.ViewAction',
			),
			'update' => array(
				'class' => 'application.modules.users.controllers.actions.UpdateAction',
			),
			'passwd' => array(
				'class' => 'application.modules.users.controllers.actions.PasswdAction',
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
			array( 'allow', // allow authenticated user to...
				'actions' => array( 'index' ),
				'roles' => array('users')
			),
			array( 'allow', // allow authenticated user to...
				'actions' => array( 'create' ),
				'roles' => array('users.create_user')
			),
			array( 'allow', // access checked inside action
				'actions' => array( 'view' ),
				'users' => array('@')
				
			),
			array( 'allow', // access checked inside action
				'actions' => array( 'update' ),
				'users' => array('@')
				
			),
			array( 'allow', // access checked inside action
				'actions' => array( 'passwd' ),
				'users' => array('@')
				
			),
			array( 'deny', // deny all users
				'users' => array( '*' ),
			),
		);

		return $rules;
	}
}