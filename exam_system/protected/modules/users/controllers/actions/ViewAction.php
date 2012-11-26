<?php
/**
 * 
 * 
 */
class ViewAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'users.view_details',
			'type' => 0,
			'description' => 'users. view details of any user',
		),
		array(
			'id' => 'users.view_self_details',
			'type' => 0,
			'description' => 'users. view only self details',
		)
	);
	
	
	
	public $rbacItemChild = array(
		'users.view_details' => 'users.view_self_details',
		'users.view_details' => 'users',
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
		
		$this->controller->headerTitle = 'Użytkownicy - wyświetl informacje o koncie';
	}
	
	
	
	/**
	 * Displays the login page
	 */
	function run($id = 0)
	{
		$this->init();
		
		// access controll
		if( !Yii::app()->user->checkAccess( 'users.view_details' ) )
		{	
			if( !(($id==Yii::app()->user->id) && Yii::app()->user->checkAccess('users.view_self_details')) )
				throw new CHttpException( 403, 'You do not have permission to view this site.' );
		}
		
		
		
		// get user
		$user = User::model()->findbyPk($id);
		
		if( !$user )
		{
			Yii::app()->user->setFlash('error', "No such user.");
			$this->controller->redirect( array( 'index' ) );
		}
		
		
		// add few menu items
		$this->controller->module->menuItems[] = array(
			'label'=>'Szczegóły konta', 
			'url'=>array('/admin/users/view/id/'.$user->id), 
			'visible'=> (Yii::app()->user->checkAccess('users.view_self_details')&&Yii::app()->user->id==$id)||Yii::app()->user->checkAccess('users.view_details')
			);
		$this->controller->module->menuItems[] = array(
			'label'=>'Edytuj konto', 
			'url'=>array('/admin/users/update/id/'.$user->id), 
			'visible'=>(Yii::app()->user->checkAccess('users.update_self_details')&&Yii::app()->user->id==$id)||Yii::app()->user->checkAccess('users.update_user')
			);
		$this->controller->module->menuItems[] = array(
			'label'=>'Zmień hasło', 
			'url'=>array('/admin/users/passwd/id/'.$user->id), 
			'visible' => Yii::app()->user->id == $id
			);

		$creator = 0;
		if( $user->create_user != '0' )
		{
			$creator = User::model()->findByPk($user->create_user);
			
			$creator = ($creator->display_name != '') ? $creator->display_name : $creator->login;
		}
		
		$this->controller->render( 'view', array(
			'user' => $user,
			'creator' => $creator,
		) );
	}
}