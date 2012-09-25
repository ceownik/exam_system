<?php
/**
 * 
 * 
 */
class LogoutAction extends KAction
{
	/**
	 * set return url
	 */
	public $returnUrl;
	
	
	
	/**
	 * logout user
	 */
	function run()
	{
		Yii::app()->user->logout();
		
		if( $this->returnUrl )
			$this->controller->redirect($this->returnUrl);
		else
			$this->controller->redirect(Yii::app()->homeUrl);
	}
}