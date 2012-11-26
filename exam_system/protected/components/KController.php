<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class KController extends CController
{
	/**
	 * rbac items for this controllers actions
	 * it should be an array of arrays key => value 
	 * with keys: id, type, description, bizrule, data
	 * where id and type are required, if description is not set it will be set to id value
	 * type can be: (0|operation)|(1|task)|(2|role)
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
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	
	
	
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	
	
	
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	
	
	/**
	 * 
	 */
	public function init()
	{
		parent::init();
		
		Yii::app()->authManager->insertAuthItems($this->rbacItems);
		
		Yii::app()->authManager->insertItemChild($this->rbacItemChild);
	}
	
	public function beforeAction($action) {
		parent::beforeAction($action);
		
		Yii::app()->clientScript->registerCssFile(Yii::app()->createAbsoluteUrl('').'/css/tiny_mce_content.css');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->createAbsoluteUrl('').'/extensions/tiny_mce/tiny_mce.js', CClientScript::POS_BEGIN);
		$this->pageTitle = Yii::app()->settings->getValue('appAdmin', 'applicationName');
		return true;
	}
}