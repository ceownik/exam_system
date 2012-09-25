<?php
/**
 * 
 * 
 */
class LoginAction extends KAction
{
	/**
	 * set different layout depending on controller
	 */
	public $layout;
	
	
	
	/**
	 * css files or script files to register for this action
	 * 
	 * should be an array of filenames
	 */
	public $css;
	public $js;
	
	
	
	/**
	 * return url
	 */
	public $returnUrl;
	
	
	
	/**
	 * prepare action
	 */
	public function init()
	{
		// set theme and layout
		if( $this->layout )
			$this->controller->layout = $this->layout;
		
		
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
	}
	
	
	
	/**
	 * Displays the login page
	 */
	function run()
	{
		$this->init();
		
		$model = new LoginForm;
		
		// set scenario to login
		$model->setScenario('login');
		

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				if( $this->returnUrl )
					$this->controller->redirect($this->returnUrl);
				else
					$this->controller->redirect(Yii::app()->user->returnUrl);
				
					
				// TODO: enable setting 'after login page' in site settings                
				// TODO: perform log after login action
			}
		}
		// display the login form
		$this->controller->render('login',array('model'=>$model,));
	}
}