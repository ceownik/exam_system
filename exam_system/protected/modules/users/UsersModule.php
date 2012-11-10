<?php

/**
 * 
 */

/**
 * 
 */
class UsersModule extends KModule {

	/**
	 * this method is called when the module is being created you may place code
	 * here to customize the module or the application
	 */
	public function init()
	{
		parent::init();
		
		$this->moduleTitle = 'Users';
	}

	// SETTERS & GETTERS


	/**
	 * this method is called before any module controller action is performed
	 * you may place customized code here
	 * @param CController $controller
	 * @param CAction $action
	 * @return boolean
	 */
	public function beforeControllerAction( $controller, $action )
	{
		$this->menuItems = array(
			array(
				'label'=>'Users list', 
				'url'=>array('/admin/users/index'),
				'visible'=>Yii::app()->user->checkAccess('users.view_users_list'),
			),
			array(
				'label'=>'Create user', 
				'url'=>array('/admin/users/create'), 
				'linkOptions' => array('class'=>'create '),
				'visible'=>Yii::app()->user->checkAccess('users.create_user'),
			),
			array(
				'label'=>'Groups', 
				'url'=>array('/admin/users/groups'), 
			),
			array(
				'label'=>'Create group', 
				'url'=>array('/admin/users/createGroup'), 
				'linkOptions' => array('class'=>'create '),
			),
		);
		
		
		if( parent::beforeControllerAction( $controller, $action ) )
		{
			$controller->headerTitle = $this->moduleTitle;
			return true;
		}
		else
			return false;
	}

	
	
	/**
	 * 
	 */
	public $menuItems;

}