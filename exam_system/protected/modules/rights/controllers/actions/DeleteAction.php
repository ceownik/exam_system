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
			Yii::app()->user->setFlash('error', "Nie znaleziono pozycji");
			$this->controller->redirect( '/admin/rights' );
		}
		else
		{
			
			if( $item->isProtected() )
			{
				Yii::app()->user->setFlash('warning', 'Pozycja nie może zostać usunięta.');
				$this->controller->redirect( '/admin/rights/list/type/'.$item->type);
			}
			
			$result = Yii::app()->authManager->removeAuthItem($name);

			if($result)
			{
				Yii::app()->user->setFlash('success', 'Pozycję usunięto poprawnie.');
				$this->controller->redirect( '/admin/rights/list/type/'.$item->type);
			}
			else
			{
				Yii::app()->user->setFlash('error', 'Błąd podczas usuwania.');
				$this->controller->redirect( '/admin/rights/list/type/'.$item->type);
			}
		}
			
	}
}