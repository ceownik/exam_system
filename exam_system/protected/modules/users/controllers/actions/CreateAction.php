<?php
/**
 * 
 * 
 */
class CreateAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'users.create_user',
			'type' => 0,
		),
		array(
			'id' => 'users.activate_user',
			'type' => 0,
		)
	);
	
	
	
	public $rbacItemChild = array(
		'users.create_user' => 'users',
		'users.activate_user' => 'users',
	);
	
	
	
	/**
	 * css files or script files to register for this action
	 * 
	 * should be an array of filenames
	 */
	public $css;
	public $js;
	
	
	
	/**
	 * prepare action
	 */
	public function init()
	{
		// register scripts and css files
		if( is_array($this->css) )
		{
			foreach( $this->css as $cssfile )
			{
				Yii::app()->clientScript->registerCssFile($cssfile);
			}
		}
		
		if( is_array($this->js) )
		{
			foreach( $this->js as $jsfile )
			{
				Yii::app()->clientScript->registerScriptFile($jsfile, CClientScript::POS_HEAD);
			}
		}
		
		$this->controller->headerTitle = 'Users - Create user';
	}
	
	
	
	/**
	 * Displays the login page
	 */
	function run()
	{
		$this->init();
		
		
		
//		// access controll
//		if( !Yii::app()->user->checkAccess( 'user.create' ) )
//			throw new CHttpException( 403, 'You do not have permission to view this site.' );
//		
//		
		$model = new User;
		
		$model->setScenario('create');
		
		$model->active_from_now = 1;
		
		$model->is_active = 1;
		
		$model->active_from_date = date("Y-m-d H:i", time());
		$model->active_to_date = date("Y-m-d H:i", time());

		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		
		if( isset( $_POST[ 'User' ] ) )
		{
			$model->attributes = $_POST[ 'User' ];

			if(!Yii::app()->user->checkAccess('users.activate_user'))
			{
				$model->is_active = 0;
			}
			
			
			if( $model->save( true, $model->attributes_to_save ) )
			{
				Yii::app()->user->setFlash('success', "User created successfully");
				$this->controller->redirect(array('/admin/users/view/id/'.$model->id));
			}
		}

		$this->controller->render( 'create', array(
			'model' => $model,
		) );
	}
}