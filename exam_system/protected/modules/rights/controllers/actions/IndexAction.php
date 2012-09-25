<?php
/**
 * 
 * 
 */
class IndexAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.view_assingments',
			'type' => 0,
			'description' => 'rights. view list of users and rights assigned to them.'
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.view_assingments' => 'rights',
		'rights.view_assingments' => 'rights.view_user_assignments',
	);
	
	
	
	/**
	 * css files or script files to register for this action
	 * 
	 * should be an array of filenames
	 */
	public $css;
	public $js;
	
	
	
	/**
	 * Displays assignments page
	 */
	function run()
	{
		$this->init();
		
		
		$filter = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filter->filters=$_GET['FiltersForm'];
		}
		
		
		
		// get user ids
		$userIds = Yii::app()->db->createCommand()
				->select( 'id, login, display_name' )
				->from( User::model()->tableName() )
				->where( "is_deleted=false AND id!=1" )
				->order( 'id' )
				->queryAll();
		
		
		$authManager = Yii::app()->authManager;

		

		foreach ( $userIds as $key => $user )
		{
			$operations = $authManager->getAuthItems( 0, $user[ 'id' ] );
			$tasks = $authManager->getAuthItems( 1, $user[ 'id' ] );
			$roles = $authManager->getAuthItems( 2, $user[ 'id' ] );
			
			$o = '';
			foreach($operations as $operation) 
			{
				$o .='<p style="margin: 0px;">'.$operation->name.'</p>';
			}
			
			$t = '';
			foreach($tasks as $task) 
			{
				$t .='<p style="margin: 0px;">'.$task->name.'</p>';
			}
			
			$r = '';
			foreach($roles as $role) 
			{
				$r .='<p style="margin: 0px;">'.$role->name.'</p>';
			}
			
			$userIds[$key]['operations'] = $o;
			$userIds[$key]['tasks'] = $t;
			$userIds[$key]['roles'] = $r;
			
			
		}
		
		$dataProvider = new CArrayDataProvider($userIds, array(
				'id'=>'users',
				'keyField'=>'id',
				'pagination'=> array(
					'pageSize'=>Yii::app()->settings->getValue('appAdmin', 'paginationPageSize'),
				),
				'sort'=>array(
					'attributes'=>array(
						'login',
					),
					'defaultOrder' => 'login asc'
				),
			)
		);
		
		$this->controller->render( 'index', array(
			//'users' => $userIds,
			//'operations' => $operations,
			//'tasks' => $tasks,
			//'roles' => $roles,
			'usersData'=> $filter->filter($dataProvider),
			'filter'=>$filter,
		) );
	}
}