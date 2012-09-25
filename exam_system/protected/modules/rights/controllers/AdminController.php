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
				'class' => 'application.modules.rights.controllers.actions.IndexAction',
			),
			'create' => array(
				'class' => 'application.modules.rights.controllers.actions.CreateAction',
			),
			'update' => array(
				'class' => 'application.modules.rights.controllers.actions.UpdateAction',
			),
			'list' => array(
				'class' => 'application.modules.rights.controllers.actions.ListAction',
			),
			'assignment' => array(
				'class' => 'application.modules.rights.controllers.actions.AssignmentAction',
			),
			'view' => array(
				'class' => 'application.modules.rights.controllers.actions.ViewAction',
			),
			'delete' => array(
				'class' => 'application.modules.rights.controllers.actions.DeleteAction',
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
				'roles' => array('rights'),
			),
			array( 'allow', 
				'actions' => array('create'), 
				'roles' => array( 'rights.create_item' ) 
			),
			array( 'allow', 
				'actions' => array('update'), 
				'roles' => array( 'rights.update_item' ) 
			),
			array( 'allow', 
				'actions' => array('list'), 
				'roles' => array( 'rights.view_list' ) 
			),
			array( 'allow', 
				'actions' => array('assignment'), 
				'roles' => array( 'rights.view_user_assignments' ) 
			),
			array( 'allow', 
				'actions' => array('view'), 
				'roles' => array( 'rights.view_item_details' ) 
			),
			array( 'allow', 
				'actions' => array('delete'), 
				'roles' => array( 'rights.delete_item' ) 
			),
			array( 'deny', // deny all users
				'users' => array( '*' ),
			),
		);

		return $rules;
	}
	
	
}