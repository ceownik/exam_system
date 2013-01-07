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
		
		$this->controller->headerTitle = 'Ustawienia';
		
		// category, name, display name
		$names = array(
			array('appAdmin', 'applicationName', 'Nazwa aplikacji'),
			array('appAdmin', 'paginationPageSize', 'Ilość pozycji na listach'),
			array('appAdmin', 'sessionTime', 'Czas trwania sesji użytkownika'),
		);
		
		$items = array();
		$errors = array();
		
		foreach($names as $name) {
			$item = Yii::app()->settings->get($name[0], $name[1]);
			$items[$name[0].'_'.$name[1]] = $item['value'];
		}
		
		if(!empty($_POST)) {
			// validate
			$items['appAdmin_paginationPageSize'] = $_POST['appAdmin_paginationPageSize'];
			$items['appAdmin_applicationName'] = $_POST['appAdmin_applicationName'];
			$items['appAdmin_sessionTime'] = $_POST['appAdmin_sessionTime'];
			if(!is_numeric($_POST['appAdmin_paginationPageSize']) || ((int)$_POST['appAdmin_paginationPageSize'] != $_POST['appAdmin_paginationPageSize'])) {
				$errors['appAdmin_paginationPageSize'] = array('Ilość elementów na listach musi być cyfrą');echo 'not int'; echo $_POST['appAdmin_paginationPageSize'];
			}
			if(strlen($_POST['appAdmin_applicationName'])<2) {
				$errors['appAdmin_applicationName'] = array('Nazwa aplikacji jest zbyt krótka');
			}
			if(!is_numeric($_POST['appAdmin_sessionTime']) || ((int)$_POST['appAdmin_sessionTime'] != $_POST['appAdmin_sessionTime'])) {
				$errors['appAdmin_sessionTime'] = array('Czas musi być cyfrą');echo 'not int'; echo $_POST['appAdmin_sessionTime'];
			}
			
			if(empty($errors)) {
				foreach($names as $name) {
					Yii::app()->settings->update($name[0], $name[1], $items[$name[0].'_'.$name[1]]);
				}
				Yii::app()->user->setFlash('success', 'Ustawienia zaktualizowano poprawnie');
				$this->controller->refresh();
			}
		}
		
		$this->controller->render( 'index', array(
			'names' => $names,
			'items' => $items,
			'errors' => $errors,
		) );
	}
}