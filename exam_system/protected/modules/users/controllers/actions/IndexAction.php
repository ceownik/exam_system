<?php
/**
 * 
 * 
 */
class IndexAction extends KAction
{
	public $rbacItems = array(
		array(
			'id' => 'users.view_users_list',
			'type' => 0,
		)
	);
	
	
	
	public $rbacItemChild = array(
		'users.view_users_list' => 'users'
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
				Yii::app()->clientScript->registerScriptFile($jsfile);
			}
		}
		
		$this->controller->headerTitle = 'Users';
	}
	
	
	
	/**
	 * Displays the login page
	 */
	function run()
	{
		$this->init();
		
		$filter = new FiltersForm;
		if (isset($_GET['FiltersForm']))
		{
			$filter->filters=$_GET['FiltersForm'];
		}
		
		//$dataProvider=new CActiveDataProvider('User');
		$users = User::model()->findAll();
		
		
		$dataProvider = new CArrayDataProvider($users, array(
						'id'=>'users',
						'keyField'=>'id',
						'pagination'=>array(
							'pageSize'=>Yii::app()->settings->getValue('appAdmin', 'paginationPageSize'),
						),
						'sort'=>array(
							'attributes'=>array(
								'id', 'login', 'display_name', 'email', 'status',
							),
							'defaultOrder' => 'create_date desc'
						),
					)
				);
		
		
		
		$this->controller->render('index',array(
			'data' => $filter->filter($dataProvider),
			'filter' => $filter,
		));
	}
}