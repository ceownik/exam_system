<?php
/**
 * 
 * 
 */
class UpdateAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.update_item',
			'type' => 0,
			'description' => 'rights. permission to update name, description, bizrule and data of operation/task/role',
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.update_item' => 'rights',
		'rights.update_item' => 'rights.view_list',
	);
	
	
	
	/**
	 * 
	 */
	function run($name)
	{
		$item = Yii::app()->authManager->getAuthItem($name);
		
		if( !$item )
		{
			Yii::app()->user->setFlash('error', "Item not found");
			$this->controller->redirect( array( 'index' ) );
		}
		
		
		if($item->type=='0')
		{
			$this->controller->headerTitle = 'Rights - update operation';
		}
		elseif($item->type=='1')
		{
			$this->controller->headerTitle = 'Rights - update task';
		}
		elseif($item->type=='2')
		{
			$this->controller->headerTitle = 'Rights - update role';
		}
		else
		{
			throw new CHttpException( 404, 'Not found' );
		}
		
		
		
		// add extra menu items
		$this->controller->module->menuItems[] = array('label'=>'View', 'url'=>array('/admin/rights/view/name/'.$item->name));
		
		
		
		$model = RbacAuthItem::model()->findByPk($name);
		
		if($model->isProtected() && Yii::app()->user->id=='1') Yii::app()->user->setFlash('notice', 'Protected item');
		
		if( isset( $_POST[ 'RbacAuthItem' ] ) )
		{
			$name = $model->name;
			
			$model->attributes = $_POST[ 'RbacAuthItem' ];
			
			if( $model->isProtected() && Yii::app()->user->id != 1 )
				$model->name = $name;
			
			if( $model->validate() )
			{
				$new = new CAuthItem(Yii::app()->authManager,$model->name,$model->type,$model->description,$model->bizrule,$model->data);
				
				Yii::app()->authManager->saveAuthItem($new);
				
				// TODO: some problems with it ;/ 
				// check if it has been really updated
					Yii::app()->user->setFlash('success', "Item updated successfully.");
					$this->controller->redirect( array( 'index' ) );
				
			}
			else
			{
				
			}
		}
		else
		{
			$model->data = $item->data;
			$model->bizrule = $item->bizrule;
		}

		$this->controller->render( 'form', array(
			'model' => $model,
		) );
	}
}