<?php
/**
 * 
 * 
 */
class ViewAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.view_item_details',
			'type' => 0,
			'description' => 'rights. permission to see item details (childen and parents of item).',
		),
		array(
			'id' => 'rights.manage_items_relations',
			'type' => 0,
			'description' => "rights. permission to add/remove item's children",
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.view_item_details' => 'rights',
		'rights.view_item_details' => 'rights.view_list',
		'rights.manage_items_relations' => 'rights.view_item_details',
	);
	
	
	
	/**
	 * Displays assignments page
	 */
	function run($name)
	{
		$this->init();
		
		
		//$item = Yii::app()->authManager->getAuthItem($name);
		$item = RbacAuthItem::model()->getAuthItem($name);
		
		if( !$item )
		{
			Yii::app()->user->setFlash('error', "Item not found");
			$this->controller->redirect( array( '/admin/rights/index' ) );
		}
		
		
		// set page header (title)
		if($item->type=='0')
		{
			$this->controller->headerTitle = 'Rights - view details of "'.$item->name.'" operation';
		}
		elseif($item->type=='1')
		{
			$this->controller->headerTitle = 'Rights - view details of "'.$item->name.'" task';
		}
		elseif($item->type=='2')
		{
			$this->controller->headerTitle = 'Rights - view details of "'.$item->name.'" role';
		}
		else
		{
			throw new CHttpException( 404, 'Not found' );
		}
		
		
		// add extra menu items
		$this->controller->module->menuItems[] = array('label'=>'Update', 'url'=>array('/admin/rights/update/name/'.$item->name));
		
		
		$authManager = Yii::app()->authManager;
		
		
		
		// form was sent
		if(isset($_POST) && !empty($_POST))
		{dump($_POST);
			// check if user has access to add/remove children
			if(Yii::app()->user->checkAccess('rights.manage_items_relations'))
			{
				// remove child
				if( isset( $_POST['remove-child'] ) )
				{
					if( isset( $_POST['child'] ) )
					{
						$childToRemove = $_POST['child'];

						if( $authManager->hasItemChild( $item->name, $childToRemove ) )
						{
							if( !$authManager->removeItemChild( $item->name, $childToRemove ) )
								throw new CHttpException( 404, 'An error occured.' );
							else
							{
								Yii::app()->user->setFlash('success', 'Child item removed successfully');
								$this->controller->redirect('/admin/rights/view/name/'.$item->name);
							}
						}
						else
						{
							Yii::app()->user->setFlash('error', 'Child does not exist');
							$this->controller->redirect('/admin/rights/view/name/'.$item->name);
						}
					}
				}

				// add child
				if( isset( $_POST['add-child'] ) )
				{
					if( isset( $_POST['child'] ) )
					{
						$childToAdd = $_POST['child'];

						if( !$authManager->hasItemChild($item->name, $childToAdd) )
						{
							if( !$authManager->addItemChild( $item->name, $childToAdd ) )
							{
								// TODO: log this error: 'An error occured.'
								throw new CHttpException( 404, $this->t->translate( 'error', '404' ) );
							}
							else
							{
								Yii::app()->user->setFlash('success', 'Child added successfully');
								$this->controller->redirect('/admin/rights/view/name/'.$item->name);
							}
						}
						else
						{
							Yii::app()->user->setFlash('error', 'Child already assigned');
							$this->controller->redirect('/admin/rights/view/name/'.$item->name);
						}
					}
				}
			}
		}
		
		
		// get children
		$directChildren = RbacAuthItem::model()->getItemChildren($item->name);
		$inheritedChildren = RbacAuthItem::model()->getChildrenRecursively($item->name);
		
		foreach($directChildren as $k => $i)
		{
			if(array_key_exists($k, $inheritedChildren))
				unset($inheritedChildren[$k]);
		}
		
		// rights to assign
		$allRights = RbacAuthItem::model()->getAuthItems();
		
		// prepare rights to assign array
		unset($allRights[$item->name]);
		
		$allParents = array();
		
		foreach(array_keys($allRights) as $k)
		{
			if(	array_key_exists($k, $directChildren) )
				unset($allRights[$k]);
			
			if( $authManager->detectLoop($item->name, $k) )
			{
				$allParents[$k] = $allRights[$k];
				unset($allRights[$k]);
			}
		}
		
		
		// get direct parents
		$directParents = RbacAuthItem::model()->getItemParents($item->name);
		
		foreach($directParents as $k => $i)
		{
			if(array_key_exists($k, $allParents))
				unset($allParents[$k]);
		}
		
		
		// change indexes for CGridView
		$directChildren2 = array();
		$inheritedChildren2 = array();
		$directParents2 = array();
		$allParents2 = array();
		$allRights2 = array();
		foreach($directChildren as $i)
		{
			$i->directlyAssigned = true;
			$directChildren2[] = $i;
		}
		foreach($inheritedChildren as $i)
			$inheritedChildren2[] = $i;
		foreach($directParents as $i)
			$directParents2[] = $i;
		foreach($allParents as $i)
			$allParents2[] = $i;
		foreach($allRights as $i)
			$allRights2[] = $i;
		
		
		
		// prepare filters and dataProviders
		$filterParents = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filterParents->filters=$_GET['FiltersForm'];
		}
		$parents = new CArrayDataProvider(array_merge($directParents2, $allParents2), array(
						'id'=>'parents',
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
		
		
		
		$filterChildren = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filterChildren->filters=$_GET['FiltersForm'];
		}
		$children = new CArrayDataProvider(array_merge($directChildren2, $inheritedChildren2), array(
						'id'=>'children',
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
		
		
		
		$filterPotentialChildren = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filterPotentialChildren->filters=$_GET['FiltersForm'];
		}
		$potentialChildren = new CArrayDataProvider($allRights2, array(
						'id'=>'children',
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
		
		
		$this->controller->render( 'view_details', array(
			'item' => $item,
//			'directChildren' => $directChildren2,
//			'inheritedChildren' => $inheritedChildren2,
//			'directParents' => $directParents2,
//			'inheritedParents' => $allParents2,
//			'rightsToAdd' => $allRights2,
			
			'parents' => $filterParents->filter($parents),
			'filterParents' => $filterParents,
			
			'children' => $filterChildren->filter($children),
			'filterChildren' => $filterChildren,
			
			'potentialChildren' => $filterPotentialChildren->filter($potentialChildren),
			'filterPotentialChildren' => $filterPotentialChildren,
		) );
		
	}
	
}