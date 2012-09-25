<?php
/**
 * 
 * 
 */
class AssignmentAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.view_user_assignments',
			'type' => 0,
			'description' => 'rights. permission to see which items are assigned do user.',
		),
		array(
			'id' => 'rights.manage_user_assignments',
			'type' => 0,
			'description' => 'rights. permission to assign/revoke items to/from user',
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.view_user_assignments' => 'rights',
		'rights.manage_user_assignments' => 'rights.view_user_assignments',
	);
	
	
	
	/**
	 * Displays assignments page
	 */
	function run($id)
	{
		$this->init();
		
		// find user
		$user = User::model()->findByPk($id);
		
		if(!$user)
		{ // check user
			throw new CHttpException( 404, 'Bad address.');
		}
		
		
		// set page name
		$name = ($user->display_name!='') ? $user->display_name : $user->login;
		$this->controller->headerTitle = 'Rights - Assignments for '. $name;
		
		
		// get auth manager
		$authManager = Yii::app()->authManager;
		
		
		
		// get user's rights (directly assigned)
		$directlyAssigned = RbacAuthItem::model()->getAuthItems(null, $user->id);
		// get user's rights (inherited)
		$testT = RbacAuthItem::model()->getUserRightsRecursively($user->id, false);
		
		// merge together
		foreach($directlyAssigned as $k => $i)
		{
			if(array_key_exists($k, $testT))
				$testT[$k] = $i;
		}
		
		
		// change array keys for data provider
		$test = array();
		foreach($testT as $t) $test[] = $t;
		
		
	
		// get all other rights (auth items)
		$allAuthItems = RbacAuthItem::model()->getAuthItems();
		
		
		
		$rightsToAssign = array();
		
		foreach( $allAuthItems as $key => $value )
		{
			if( !(array_key_exists( $key, $testT ) && $testT[$key]->directlyAssigned) )
				$rightsToAssign[] = $value;
		}
		
		
		
		
		// prepare filters and dataProveders
		$filterAssignedRights = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filterAssignedRights->filters=$_GET['FiltersForm'];
		}
		$assignedRights = new CArrayDataProvider($test, array(
				'id'=>'authItemsTest',
				'keyField'=>'name',
				'pagination'=>array(
					'pageSize'=>Yii::app()->settings->getValue('appAdmin', 'paginationPageSize'),
				),
				'sort'=>array(
					'attributes'=>array(
						'name', 'description', 'type'
					),
					'defaultOrder' => 'name asc'
				),
			)
		);
		
		
		$filterRightsToAssign = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filterRightsToAssign->filters=$_GET['FiltersForm'];
		}
		$rightsToAssignDP = new CArrayDataProvider($rightsToAssign, array(
				'id'=>'itemsToAssign',
				'keyField'=>'name',
				'pagination'=>array(
					'pageSize'=>Yii::app()->settings->getValue('appAdmin', 'paginationPageSize'),
				),
				'sort'=>array(
					'attributes'=>array(
						'name', 'description', 'type'
					),
					'defaultOrder' => 'name asc'
				),
			)
		);
		
		
		
		
		// if form was submitted
		if(isset($_POST) && !empty($_POST))
		{
			// check if user has access to assign/revoke permissions
			if(Yii::app()->user->checkAccess('rights.manage_user_assignments'))
			{
				// if assign form was submitted
				if( isset($_POST['assign']) && $_POST['assign']==='true' )
				{
					// TODO: access controll

					if( isset( $_POST['assign-item'] ) )
					{
						$rightToAssign = $_POST['assign-item'];

						if( !$authManager->isAssigned($rightToAssign, $user->id ))
								$authManager->assign( $rightToAssign, $user->id );

						Yii::app()->user->setFlash('success', 'Right was successfully assigned to user.');
						$this->controller->redirect( array( '/admin/rights/assignment/id/'.$user->id) );
					}
				}



				// if revoke link has been clicked
				if( isset($_POST['revoke']) && $_POST['revoke']==='true' )
				{
					if( isset( $_POST['revoke-item'] ) )
					{
						$rightToRevoke = $_POST['revoke-item'];

						if( $authManager->isAssigned($rightToRevoke, $user->id ))
								$authManager->revoke( $rightToRevoke, $user->id );

						Yii::app()->user->setFlash('success', 'Right was successfully revoked from user.');
						$this->controller->redirect( array( '/admin/rights/assignment/id/'.$user->id) );
					}
				}
			}
		}
		
		
		$this->controller->render( 'assign', array(
			'user'=>$user,
			'assignedRights'=>$filterAssignedRights->filter($assignedRights),
			'filterAssignedRights'=>$filterAssignedRights,
			'rightsToAssign'=>$filterRightsToAssign->filter($rightsToAssignDP),
			'filterRightsToAssign' => $filterRightsToAssign,
			'allItems'=>$allAuthItems,
		) );
	}
	
			
}