<?php
/**
 * 
 * 
 */
class IndexAction extends KAction
{
	public $rbacItems = array(
		
	);
	
	
	
	public $rbacItemChild = array(
		
	);
	
	
	
	/**
	 * Displays assignments page
	 */
	function run()
	{
		$this->init();
		
		$this->controller->headerTitle = 'Settings - Application settings';
		
		
		
		
		
		$this->controller->render( 'index', array(
			
		) );
	}
}