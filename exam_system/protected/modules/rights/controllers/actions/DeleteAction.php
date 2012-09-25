<?php
/**
 * 
 * 
 */
class DeleteAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.delete_item',
			'type' => 0,
			'description' => 'rights. permission to delete items',
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.delete_item' => 'rights',
		'rights.delete_item' => 'rights.view_list',
	);
	
	
	
	/**
	 * Displays the login page
	 */
	function run($name)
	{
		
		//$item = Yii::app()->authManager->getAuthItem($name);
		
		$item = RbacAuthItem::model()->findByPk($name);
		
		
		
		if( !$item )
		{
			Yii::app()->user->setFlash('error', "Item not found");
			$this->controller->redirect( '/admin/rights' );
		}
		else
		{
			
			if( $item->isProtected() )
			{
				Yii::app()->user->setFlash('warning', 'Item can not be deleted.');
				$this->controller->redirect( '/admin/rights/list/type/'.$item->type);
			}
			
			$result = Yii::app()->authManager->removeAuthItem($name);

			if($result)
			{
				Yii::app()->user->setFlash('success', 'Item has been successfully deleted.');
				$this->controller->redirect( '/admin/rights/list/type/'.$item->type);
			}
			else
			{
				Yii::app()->user->setFlash('error', 'Error while deleting item.');
				$this->controller->redirect( '/admin/rights/list/type/'.$item->type);
			}
		}
			
	}
}