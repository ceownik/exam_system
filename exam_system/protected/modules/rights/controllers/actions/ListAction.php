<?php
/**
 * 
 * 
 */
class ListAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.view_list',
			'type' => 0,
			'description' => 'rights. view list of operations/tasks/roles',
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.view_list' => 'rights',
	);
	
	
	
	
	/**
	 * Displays the login page
	 */
	function run($type = '0')
	{
		$items = null;
		
		switch($type)
		{
			case '0':
			case 'operations':
				$this->controller->headerTitle = 'Rights - Display operations';
				//$items = Yii::app()->authManager->getOperations();
				$items = RbacAuthItem::model()->getAuthItems(0);
				break;
			case '1':
			case 'tasks':
				$this->controller->headerTitle = 'Rights - Display tasks';
				//$items = Yii::app()->authManager->getTasks();
				$items = RbacAuthItem::model()->getAuthItems(1);
				break;
			case '2':
			case 'roles':
				$this->controller->headerTitle = 'Rights - Display roles';
				//$items = Yii::app()->authManager->getRoles();
				$items = RbacAuthItem::model()->getAuthItems(2);
				break;
			default:
				
				
		}
		
		$items2 = array();
		foreach($items as $i)
			$items2[] = $i;
		
		
		
		
		$filter = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filter->filters=$_GET['FiltersForm'];
		}
		$dataProvider = new CArrayDataProvider($items2, array(
				'id'=>'authItems',
				'keyField'=>'name',
				'pagination'=>array(
					'pageSize'=>Yii::app()->settings->getValue('appAdmin', 'paginationPageSize'),
				),
				'sort'=>array(
					'attributes'=>array(
						'name', 'description',
					),
					'defaultOrder' => 'name asc'
				),
			)
		);
		
		$this->controller->render( 'list_items', array(
			'items' => $filter->filter($dataProvider),
			'filter' => $filter,
		) );
	}
}