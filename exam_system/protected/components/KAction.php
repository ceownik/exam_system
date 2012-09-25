<?php

/**
 * Action class
 * 
 */
class KAction extends CAction 
{
	/**
	 * rbac items for this controllers actions
	 * it should be an array of arrays key => value 
	 * with keys: id, type, description, bizrule, data
	 * where id and type are required, if description is not set it will equal to id
	 * it will be use for creating rbac auth items in authManager
	 * you should create only basic operations here, and user Rights module to create tasks, roles and dependancies between them
	 */
	public $rbacItems;
	
	
	
	/**
	 * array of item child dependancies that you wanna create in code
	 * parent item should be key and child item should be value
	 */
	public $rbacItemChild;
	
	
	
	/**
	 * 
	 */
	public function __construct($controller, $id)
	{
		parent::__construct($controller, $id);
		
		Yii::app()->authManager->insertAuthItems($this->rbacItems);
		
		Yii::app()->authManager->insertItemChild($this->rbacItemChild);
	}
	
	
	
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

}