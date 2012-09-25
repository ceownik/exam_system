<?php
/**
 * 
 * 
 */
class CreateAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'rights.create_item',
			'type' => 0,
			'description' => 'rights. permission to create items',
		),
	);
	
	
	
	public $rbacItemChild = array(
		'rights.create_item' => 'rights',
	);
	
	
	
	/**
	 * css files or script files to register for this action
	 * 
	 * should be an array of filenames
	 */
	public $css;
	public $js;
	
	
	
	/**
	 * prepare action
	 */
	public function init()
	{
		// register scripts and css files
		if( is_array($this->css) )
		{
			foreach( $this->css as $cssfile )
			{
				Yii::app()->clientScript->registerCssFile($cssfile);
			}
		}
		
		if( is_array($this->js) )
		{
			foreach( $this->js as $jsfile )
			{
				Yii::app()->clientScript->registerScriptFile($jsfile, CClientScript::POS_HEAD);
			}
		}
		
		
	}
	
	
	
	/**
	 * Displays the login page
	 */
	function run($type = '0')
	{
		$this->init();
		
		
		if($type=='0')
		{
			$type = 0;
			$this->controller->headerTitle = 'Rights - create operation';
		}
		elseif($type=='1')
		{
			$type = 1;
			$this->controller->headerTitle = 'Rights - create task';
		}
		elseif($type=='2')
		{
			$type = 2;
			$this->controller->headerTitle = 'Rights - create role';
		}
		else
		{
			throw new CHttpException( 403, 'You do not have permission to view this site.' );
		}
		
	
		
		$model = new RbacAuthItem;
		
		$model->setScenario('create');
		
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		
		if( isset( $_POST[ 'RbacAuthItem' ] ) )
		{
			$model->attributes = $_POST[ 'RbacAuthItem' ];

			$model->type = $type;
			
			if( $model->validate() )
			{
				if( Yii::app()->authManager->createAuthItem( $model->name, $model->type, $model->description, $model->bizrule, $model->data) )
				{
					Yii::app()->user->setFlash('success', "Item created successfully.");
					$this->controller->redirect( array( '/admin/rights/list/type/'.$type ) );
				}
			}
			else
			{
				
			}
		}

		$this->controller->render( 'form', array(
			'model' => $model,
		) );
	}
}