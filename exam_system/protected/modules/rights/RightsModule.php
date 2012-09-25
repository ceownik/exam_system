<?php

/**
 * 
 */

/**
 * 
 */
class RightsModule extends KModule 
{
	/**
	 * 
	 */
	public $authManager = 'authManager';
	
	

	/**
	 * this method is called when the module is being created you may place code
	 * here to customize the module or the application
	 */
	public function init()
	{
		parent::init();
		
		$this->moduleTitle = 'Rights';
		
		Yii::import('application.modules.rights.models.*');
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
				'label'=>'Assignments', 
				'url'=>array('/admin/rights/index'),
				'visible'=>Yii::app()->user->checkAccess('rights.view_assingments'),
			),
			array(
				'label'=>'Operations', 
				'url'=>array('/admin/rights/list/type/0'), 
				'linkOptions' => array(),
				'visible'=>Yii::app()->user->checkAccess('rights.view_list'),
			),
			array(
				'label'=>'Tasks', 
				'url'=>array('/admin/rights/list/type/1'), 
				'linkOptions' => array(),
				'visible'=>Yii::app()->user->checkAccess('rights.view_list'),
			),
			array(
				'label'=>'Roles', 
				'url'=>array('/admin/rights/list/type/2'), 
				'linkOptions' => array(),
				'visible'=>Yii::app()->user->checkAccess('rights.view_list'),
			),
			array(
				'label'=>'Create', 
				'linkOptions'=>array(
					'class'=>'parent create'
				), 
				'visible'=>Yii::app()->user->checkAccess('rights.create_item'),
				'items'=>array(
					array(
						'label'=>'Operation', 
						'url'=>array('/admin/rights/create/type/0'), 
						'linkOptions' => array('class'=>'',)
					),
					array(
						'label'=>'Task', 
						'url'=>array('/admin/rights/create/type/1'), 
						'linkOptions' => array('class'=>'',)
					),
					array(
						'label'=>'Role', 
						'url'=>array('/admin/rights/create/type/2'), 
						'linkOptions' => array('class'=>'',)
					),
				)
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