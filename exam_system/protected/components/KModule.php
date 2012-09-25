<?php
/**
 * KModule is the customized base Module class.
 * All Modules classes for this application should extend from this base class.
 * 
 * all modules should implement install class
 */
class KModule extends CWebModule
{
	/**
	 * module title that will be displayed in administration panel 
	 */
	public $moduleTitle;
	
	
	
	/**
	 * 
	 */
	public $menuItems = array();
	
	
	
	/**
	 * if module is a front-end module
	 */
	public $isFrontEnd = false;
	
	
	
	/**
	 * if module is a back-end module
	 * (if should be shown in admin panel menu)
	 */
	public $isBackEnd = false;
	
	
	
	/**
	 * translate function alias
	 * 
	 * can be called from any module
	 * if called from module - appends prefix to category name
	 */
	public static function t( $category, $message, $params=array ( ), $source=NULL, $language=NULL, $module = null )
	{
		if( $module === null )
			$prefix = Yii::app()->controller->module->name.'Module.';
		else
			$prefix = '';
		
		if($category===null)
			$category = Yii::app()->controller->module->name;
		
		$category = $prefix . $category;
			
		return Yii::t($category, $message, $params=array ( ), $source=NULL, $language=NULL);
	}
}