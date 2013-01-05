<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class KAdminController extends KController
{
	/**
	 * 
	 */
	public $headerTitle;
	
	
	
	/**
	 * prepare controller
	 */
	public function init()
	{
		// set different session for admin controllers
		Yii::app()->setComponents(array(
			'user'=>array(
				'class'=>'KWebUser',
				'stateKeyPrefix'=>'admin',
				'loginUrl'=>array('admin/login'),
				'allowAutoLogin'=>false,
			),
		), false);
		
		parent::init();	
		Yii::app()->theme = 'admin';
		
		
		
		// import admin panel classes
		Yii::import('application.extensions.kgridview.KGridView.php');
		Yii::import('application.extensions.kgridview.KButtonColumn.php');
		
		Yii::import('application.extensions.kgridview.FiltersForm');
		
	}
	
	
	
	/**
	 * you can override this method in child controller to set your own filters
	 * (you should override accossRules method to), 
	 * you can also add additional filters to those existing here by overriding
	 * this method this way:
	 * 
	 * public function filters()
	 * {
	 * 	$newFilters = array(
	 * 		// your filters
	 * 	);
	 * 
	 * 	$oldFilters = parent::filters();
	 * 	return array_merge( $oldFilters, $newFilters );
	 * }
	 * @return array action filters
	 */
	public function filters()
	{
		// skip filters for superadmin user
		if( Yii::app()->user->id == '1' )
			return array();
		
		return array(
			// perform access control for CRUD operations (basic access control)
			// by default deny all users override accessRules method to change
			// this
			'accessControl',
		);
	}
	
	
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$rules = array(
			array( 'allow', // allow guest user to...
				'actions' => array( 'login', 'error', 'logout' ),
				'users' => array( '*' ),
			),
			array( 'allow', // allow authenticated user to...
				'actions' => array( '*' ),
				//'users' => array( '@' ),
				'roles' => array('admin')
			),
			array( 'deny', // deny all users
				'users' => array( '*' ),
			),
		);

		return $rules;
	}
	
	
	
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/admin_panel';
	
	
	
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
}