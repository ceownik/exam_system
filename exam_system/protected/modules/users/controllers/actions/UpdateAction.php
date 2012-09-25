<?php
/**
 * 
 * 
 */
class UpdateAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'users.update_user',
			'type' => 0,
			'description' => "update any user's details"
		),
		array(
			'id' => 'users.update_self_details',
			'type' => 0,
		),
		array(
			'id' => 'users.update_activity',
			'type' => 0,
		)
	);
	
	
	
	public $rbacItemChild = array(
		'users.update_user' => 'users',
		'users.update_activity' => 'users',
	);
	
	
	
	/**
	 * Displays the login page
	 */
	function run($id)
	{
		// access controll
		if( !Yii::app()->user->checkAccess( 'users.update_user' ) )
		{	// maybe user can update self details
			if( !( $id == Yii::app()->user->id  &&  Yii::app()->user->checkAccess('users.update_self_details') ) )
				throw new CHttpException( 403, 'You do not have permission to view this site.' );
		}
			
		
		
		$model = User::model()->findByPk($id);
		
		
		if( !$model )
		{
			Yii::app()->user->setFlash('error', "No such user.");
			$this->controller->redirect( array( 'index' ) );
		}
		
		
		$title = ($model->display_name) ? $model->display_name : $model->login;
		$this->controller->headerTitle = 'Users - Update user: ' . $title;
		
		
		if( $model->is_deleted )
		{
			Yii::app()->user->setFlash('error', "User is deleted");
			$this->controller->redirect( array( 'index' ) );
		}
		
		
		// add few menu items
		$this->controller->module->menuItems[] = array(
			'label'=>'Details', 
			'url'=>array('/admin/users/view/id/'.$model->id), 
			'visible'=> (Yii::app()->user->checkAccess('users.view_self_details')&&Yii::app()->user->id==$id)||Yii::app()->user->checkAccess('users.view_details')
			);
		$this->controller->module->menuItems[] = array(
			'label'=>'Update', 
			'url'=>array('/admin/users/update/id/'.$model->id), 
			'visible'=>(Yii::app()->user->checkAccess('users.update_self_details')&&Yii::app()->user->id==$id)||Yii::app()->user->checkAccess('users.update_user')
			);
		$this->controller->module->menuItems[] = array(
			'label'=>'Change password', 
			'url'=>array('/admin/users/passwd/id/'.$model->id), 
			'visible' => Yii::app()->user->id == $id
			);
		
		
		$model->setScenario('update');
		
		$model->active_from_now = '0';
		
		$model->active_from_date = ($model->active_from == '0') ? '' : date("Y-m-d H:i", $model->active_from);
		
		$model->active_to_date = ($model->active_to == '0') ? '' : date("Y-m-d H:i", $model->active_to);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		
		if( isset( $_POST[ 'User' ] ) )
		{
			$active_from_date = $model->active_from_date;
			$active_to_date = $model->active_to_date;
			$is_active = $model->is_active;
			
			$model->attributes = $_POST[ 'User' ];
			
			if(!Yii::app()->user->checkAccess('users.update_activity'))
			{
				$model->active_to_date = $active_from_date;
				$model->active_to_date = $active_to_date;
			}
			
			if(!Yii::app()->user->checkAccess('users.activate_user'))
				$model->is_active = $is_active;
				
			
			if( $model->save( true, $model->attributes_to_save ) ) 
			{
				Yii::app()->user->setFlash('success', "User updated successfully");
				$this->controller->redirect( array( 'view', 'id' => $model->id ) );
			}
		}

		$this->controller->render( 'update', array(
			'model' => $model,
		) );
	}
}