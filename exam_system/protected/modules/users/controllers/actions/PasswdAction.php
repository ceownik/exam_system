<?php
/**
 * 
 * 
 */
class PasswdAction extends KAction
{
	/**
	 * Displays the login page
	 */
	function run($id)
	{
		// access controll
		if( !(Yii::app()->user->id == $id ) )
		{
			throw new CHttpException( 403, 'You do not have permission to view this site.' );
		}
		
		
		$model = User::model()->findByPk($id);
		
		
		if( !$model )
		{
			Yii::app()->user->setFlash('error', "No such user.");
			$this->controller->redirect( array( 'index' ) );
		}
		
		
		$title = ($model->display_name) ? $model->display_name : $model->login;
		$this->controller->headerTitle = 'Users - Change password: ' . $title;
		
		
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
		
		
		$model->setScenario('passwd');
		
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		
		if( isset( $_POST[ 'User' ] ) )
		{
			$model->attributes = $_POST[ 'User' ];

			
			if( $model->save( true, $model->attributes_to_save ) ) 
			{
				Yii::app()->user->setFlash('success', "Password changed successfully");
				$this->controller->redirect( array( 'view', 'id' => $model->id ) );
			}
		}

		$this->controller->render( 'passwd', array(
			'model' => $model,
		) );
	}
}