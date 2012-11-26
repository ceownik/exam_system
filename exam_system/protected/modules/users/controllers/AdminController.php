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
	
	public function getGroup($id) {
		$model = UserGroup::model()->findByPk($id);
		if($model==null) {
			KThrowException::throw404();
			exit;
		}
		return $model;
	}
	
	/**
	 * ACTIONS
	 */
	
	public function actionGroups() {
		$model = new UserGroup;
		$model->unsetAttributes();
		if(isset($_GET['UserGroup'])) 
			$model->attributes = $_GET['UserGroup'];
		
		$this->render('groups', array(
			'model'=>$model,
		));
	}
	
	public function actionViewGroup($id) {
		$this->headerTitle = 'Wyświetl grupę';
		
		$model = $this->getGroup($id);
		
		// form was sent
		if(isset($_POST) && !empty($_POST))
		{
			// check if user has access to add/remove children
			//if(Yii::app()->user->checkAccess('rights.manage_items_relations'))
			{
				// remove child
				if( isset( $_POST['remove-user'] ) )
				{
					if( isset( $_POST['user'] ) )
					{
						$userToRemove = $_POST['user'];
						$assignment = $model->hasUser($userToRemove);
						if( $assignment )
						{
							if( !$assignment->delete() )
								throw new CHttpException( 404, 'An error occured.' );
							else
							{
								Yii::app()->user->setFlash('success', 'User removed successfully');
								$this->redirect('/admin/users/viewGroup/id/'.$model->primaryKey);
							}
						}
						else
						{
							Yii::app()->user->setFlash('error', 'User does not exist');
							$this->redirect('/admin/users/viewGroup/id/'.$model->primaryKey);
						}
					}
				}

				// add child
				if( isset( $_POST['add-user'] ) )
				{
					if( isset( $_POST['user'] ) )
					{
						$userToAdd = $_POST['user'];
						$assignment = $model->hasUser($userToAdd);

						if( !$assignment )
						{
							if( !$model->addUser($userToAdd) )
							{
								// TODO: log this error: 'An error occured.'
								throw new CHttpException( 404, $this->t->translate( 'error', '404' ) );
							}
							else
							{
								Yii::app()->user->setFlash('success', 'User added successfully');
							$this->redirect('/admin/users/viewGroup/id/'.$model->primaryKey);
							}
						}
						else
						{
							Yii::app()->user->setFlash('error', 'User already assigned');
							$this->redirect('/admin/users/viewGroup/id/'.$model->primaryKey);
						}
					}
				}
			}
		}
		
		$user = new User('search');
		$user->unsetAttributes();
		if(isset($_GET['User'])) {
			$user->attributes = $_GET['User'];
		}
		
		$this->render('view-group',array(
			'model'=>$model,
			'userModel'=>$user,
		));
	}
	
	public function actionCreateGroup() {
		$this->headerTitle = 'Utwórz grupę';
		
		$model = new UserGroup;
		$model->setScenario('create');
		
		if(isset($_POST['UserGroup']))
		{
			$model->attributes = $_POST['UserGroup'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item created successfully.");
					$this->redirect(array('/admin/users/groups'));
				}
			}
		}
		$this->render('create-group',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdateGroup($id) {
		$this->headerTitle = 'Update group';
		
		$model = $this->getGroup($id);
		
		if(isset($_POST['UserGroup']))
		{
			$model->attributes = $_POST['UserGroup'];
			
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', "Item changed successfully.");
					$this->redirect(array('/admin/users/groups'));
				}
			}
		}
		
		$this->render('create-group',array(
			'model'=>$model,
		));
	}
	
	public function actionDeleteGroup($id) {
		$model = $this->getGroup($id);
		
		$model->is_deleted = true;
		$model->save();
		
		$this->redirect(array('/admin/users/groups'));
	}
}