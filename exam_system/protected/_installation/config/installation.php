<?php

// This is the installation configuration. 
// Will be used during installation process
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..',
	'name'=>'Installation',
	'language'=>'en',
	
	'controllerPath'=>'protected/installation/controllers',
	'defaultController' => 'site',
	'viewPath' => 'protected/installation/views',
	
	// preloading 'log' component
	'preload'=>array('log', 'settings'),

	// autoloading model and component classes
	'import'=>array(
		'application.components.*',
		'application.installation.*',
		'application.modules.users.models.*',
	),

	'modules'=>array(
	),

	// application components
	'components'=>array(
		
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			
			'urlFormat'=>'path',
			'rules'=>array(
				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
		),
		
		// fake MySQL database
//		'db'=>array(
//			'class' => 'KDbConnection',
//			'connectionString' => 'mysql:host=localhost;dbname=localhost',
//			'emulatePrepare' => true,
//			'username' => 'localhost',
//			'password' => 'localhost',
//			'charset' => 'utf8',
//			'tablePrefix' => 'kcms_',
//		),

		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		 ),

		
	),

	// application-level parameters
	'params'=>array(
	),
);