<?php

class AdminController extends KAdminController
{
	public $rbacItems = array(
		
	);
	
	
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'index' => array(
				'class' => 'application.modules.settings.controllers.actions.IndexAction',
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
				'actions' => array('index'),
				'roles' => array('settings'),
			),
		);

		return $rules;
	}
	
	
}