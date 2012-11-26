<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
	'language'=>'pl',
	
	// preloading 'log' component
	'preload'=>array('log', 'settings'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.users.models.*',
		'application.extensions.*',
		'application.extensions.helpers.*',
		'application.extensions.errors.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1234',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
		'users' => array(
			'class' => 'application.modules.users.UsersModule',
			'isBackEnd' => true,
		),
		'rights' => array(
			'class' => 'application.modules.rights.RightsModule',
			'authManager' => 'authManager',
			'isBackEnd' => true,
		),
		'settings' => array(
			'class' => 'application.modules.settings.SettingsModule',
			'componentName' => 'settings',
			'isBackEnd' => true,
		),
		'exam' => array(
			'class' => 'application.modules.exam.ExamModule',
			'isBackEnd' => true,
		),
		'question' => array(
			'class' => 'application.modules.question.QuestionModule',
			'isBackEnd' => true,
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class'=>'KWebUser',
			'userTable'=>'user',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'class'=>'KUrlManager',
			'urlFormat'=>'path',
			'rules'=>array(
				
				'<action:(login|logout)>'=>'site/<action>',
				
				// admin actions
				'admin' => 'admin',
				'admin/<sth:(index|login|logout)>' => 'admin/<sth>',
				'admin/<module:\w+>' => '<module>/admin',
				'admin/<module:\w+>/<action:\w+>' => '<module>/admin/<action>',
				'admin/<module:\w+>/<action:\w+>/<item:.*>' => '<module>/admin/<action>/<item>',
				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
		),
		
		// MySQL database
//		'db'=>array(
//			'class' => 'KDbConnection',
//			'connectionString' => 'mysql:host=localhost;dbname=kcms',
//			'emulatePrepare' => true,
//			'username' => 'kcms',
//			'password' => '123456',
//			'charset' => 'utf8',
//			'tablePrefix' => 'kcms_',
//		),
		'db'=> require(dirname(__FILE__).'/db-config.php'),
		
		'authManager'=>array(
			'class'=>'KDbAuthManager',
			'connectionID'=>'db',
			'itemTable' => 'rights_items',
			'itemChildTable' => 'rights_item_child',
			'assignmentTable' => 'rights_assignment',
			'protectedItemsTable' => 'rights_protected',
			'inDebugMode' => true,
		),
		
		'settings'=>array(
			'class' => 'KSettings',
			'connectionID' => 'db',
			'settingsTable' => 'settings',
		),
		
		'messages'=>array(
			'class'=>'KPhpMessageSource',
			'cachingDuration'=>0,
			'forceTranslation'=>true,
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// show log messages on web pages
//				array(
//					'class'=>'CWebLogRoute',
//					'categories' => 'system.db.*',
//				),
			),
		),
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);