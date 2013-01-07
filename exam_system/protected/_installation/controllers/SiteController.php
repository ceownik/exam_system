<?php

class SiteController extends CController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}
	
	
	
	public function filters()
	{
		return array(
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
				'actions' => array(  ),
				'users' => array( '*' ),
			),
		);
		return $rules;
	}
	
	
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// register jquery
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile('js/installation.js');
		
		
		// check if required files/directories are writable
		$configWritable = is_writable(APP_BASE.'/protected/config/db-config.php');
		$assetsWritable = is_writable(APP_BASE.'/assets');
		$runtimeWritable = is_writable(APP_BASE.'/protected/runtime');
		
		
		// installation steps
		$step = 1;
		
		if(isset($_POST['request']) && Yii::app()->request->isAjaxRequest)
		{
			if($_POST['request'] == 'loadMainView')
			{
				$html = $this->renderPartial('main_form', array(), true);
				
				echo CJSON::encode(array(
					'status' => 'success',
					'html' => $html,
					'title' => 'Krok 1 -> konfiguracja bazy danych',
				));
			}
			elseif($_POST['request'] == 'checkDbConnection')
			{
				$data = array();
				parse_str($_POST['data'], $data);
				
				$connection = new CDbConnection;
		
				$connection->connectionString =  'mysql:host='.$data['host'].';dbname='.$data['database'];
				$connection->username = $data['user'];
				$connection->password = $data['password'];

				try {
					$connection->setActive(true);
				}
				catch(CDbException $e)
				{
					echo CJSON::encode(array(
						'status' => 'success',
						'connection' => false,
						'msg' => 'Błąd podczas próby nawiązania połączenia',
					));
					exit;
				}
				
				
				// add config to session
				Yii::app()->session->add('connectionString', 'mysql:host='.$data['host'].';dbname='.$data['database']);
				Yii::app()->session->add('username', $data['user']);
				Yii::app()->session->add('password', $data['password']);
				
				
				echo CJSON::encode(array(
					'status' => 'success',
					'connection' => true,
					'msg' => 'Połączenie zostało nawiązane poprawnie',
					'tmp' => $connection->active
				));
			}
			elseif($_POST['request'] == 'loadSettingsView')
			{
				$html = $this->renderPartial('settings_form', array(), true);
				
				echo CJSON::encode(array(
					'status' => 'success',
					'html' => $html,
					'title' => 'Krok 2 -> konfiguracja aplikacji',
				));
			}
			elseif($_POST['request'] == 'installApplication')
			{
				$data = array();
				parse_str($_POST['data'], $data);
				
				$valid = true;
				
				if(strlen($data['login']) == 0 )
				{
					$valid = false;
				}
				
				if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					$valid = false;
				}
				
				if($data['password_user'] != $data['password_repeat'])
				{
					$valid = false;
				}
				
				if(strlen($data['title'])<2) {
					$valid = false;
				}
				
				if(!$valid)
				{
					echo CJSON::encode(array(
						'status' => 'validation',
						'msg' => 'Proszę poprawnie wypełnić wszystkie pola.',
					));
					exit;
				}
				
				Yii::app()->session->add('login', $data['login']);
				Yii::app()->session->add('email', $data['email']);
				Yii::app()->session->add('password_user', $data['password_user']);
				Yii::app()->session->add('appName', $data['title']);
				
				// perform installation
				if($this->install())
				{
					$html = 'Aplikacja została zainstalowana. Należy usunąć katalog instalacyjny.';
					echo CJSON::encode(array(
						'status' => 'success',
						'html' => $html,
						'title' => 'Dziękujemy',
					));
				}
				else
				{
					$html = 'Wystąpił nieoczekiwany błąd podczas instalacji aplikacji';
					echo CJSON::encode(array(
						'status' => 'error',
						'html' => $html,
						'title' => 'Przepraszamy',
					));
				}
			}
			exit;
		}
		
		foreach($_SESSION as $key => $value)
		{
			unset($_SESSION[$key]);
		}
		
		$this->render('index', array(
			'configWritable' => $configWritable,
			'assetsWritable' => $assetsWritable,
			'runtimeWritable' => $runtimeWritable,
			'step' => $step,
		));
	}
	
	
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$error=Yii::app()->errorHandler->error;
		if($error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	
	/**
	 * install database tables and insert basic values
	 */
	private function install() 
	{
		$data = array(
			'connectionString' => Yii::app()->session->get('connectionString'),
			'username' => Yii::app()->session->get('username'),
			'password' => Yii::app()->session->get('password'),
		);
		
		$db = new CDbConnection;
		
		$db->connectionString =  $data['connectionString'];
		$db->username = $data['username'];
		$db->password = $data['password'];
		
		try 
		{
			$db->setActive(true);
		}
		catch(CDbException $e)
		{
			return false;
		}
		
		
		$fp = fopen(APP_BASE.'/protected/config/db-config.php', 'w');
		$html = 
"<?php

// configuration for database connection
return array(
	'class' => 'KDbConnection',
	'connectionString' => '".$data['connectionString']."',
	'emulatePrepare' => true,
	'username' => '".$data['username']."',
	'password' => '".$data['password']."',
	'charset' => 'utf8',
	'tablePrefix' => '',
);	
		";
		fwrite($fp, $html);
		fclose($fp);
		
		for($i=0; $i<=count($this->tables); $i++)
		{
			// create tables
			$db->schema->refresh();
			$existingTables = $db->schema->tableNames;
			
			foreach($this->tables as $name => $sql)
			{
				if(in_array($name, $existingTables))
				{
					try
					{
						$db->createCommand()->dropTable($name);
					}
					catch(CDbException $e) {
						
					}
				}
			}
		}
		// create tables
		$db->schema->refresh();
		$existingTables = $db->schema->tableNames;
		
		foreach($this->tables as $name => $sql)
		{
			$db->createCommand($sql)->execute();
		}
		
		
		// insert values
		// create user
		Yii::import('application.modules.users.models.User', true);
		$db->createCommand()->insert('user', array(
			'login' => Yii::app()->session->get('login'),
			'email' => Yii::app()->session->get('email'),
			'password' => User::encrypt(Yii::app()->session->get('password_user')),
			'is_active' => true,
			'create_user' => 0,
			'create_date' => time(),
			'last_update_user' => 0,
		));
		
		// create authitems
		foreach($this->rights_items as $key => $v)
		{
			if(isset($v['data']))
				$v['data'] = serialize($v['data']);
			
			$db->createCommand()
				->insert('rights_items', $v);
			
			$db->createCommand()
				->insert('rights_protected', array(
					'itemname' => $v['name'],
				));
		}
		
		// create item - child relations
		foreach($this->rights_item_child as $item => $child)
		{
			$db->createCommand()
				->insert('rights_item_child', array(
					'parent' => $item,
					'child' => $child,
				));
		}
		
		// create settings
		foreach($this->settings as $s)
		{
			$s['type'] = 'serialize';
			$s['value'] = serialize($s['value']);
			
			$db->createCommand()
				->insert('settings', $s);
		}
		
		
		$db->createCommand()->update('settings', array(
			'value' => serialize(Yii::app()->session->get('appName')),
		),'category = "appAdmin" and name = "applicationName"');
		return true;
	}
	
	
	private $tables = array(
		'user' => "CREATE TABLE user (
				`id` int(11) not null auto_increment,
				`login` varchar(255) collate utf8_unicode_ci not null,
				`display_name` varchar(255) collate utf8_unicode_ci not null default '',
				`email` varchar(255) collate utf8_unicode_ci not null,
				`password` varchar(128) collate utf8_unicode_ci not null,

				`is_active` boolean not null default false,
				`active_from` int(11) not null default 0,
				`active_to` int(11) not null default 0,
				`is_deleted` boolean not null default false,

				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` timestamp default current_timestamp() on update current_timestamp(),
				`last_update_user` int(11) not null,

				`last_login_date` int(11),

				primary key (`id`),
				unique (`login`),
				unique (`email`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users';
		",
		
		'settings' => "CREATE TABLE settings (
				`category` varchar(255) collate utf8_unicode_ci not null,
				`name` varchar(255) collate utf8_unicode_ci not null,
				`value` text collate utf8_unicode_ci not null,
				`type` varchar(16) collate utf8_unicode_ci not null,

				primary key (`category`, `name`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='settings';
		",
		
		'rights_items' => "create table rights_items (
				`name`                 varchar(64) not null,
				`type`                 integer not null,
				`description`          text,
				`bizrule`              text,
				`data`                 text,
				primary key (`name`)
			) engine InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'rights_item_child' => "create table rights_item_child (
				`parent`               varchar(64) not null,
				`child`                varchar(64) not null,
				primary key (`parent`,`child`),
				foreign key (`parent`) references rights_items (`name`) on delete cascade on update cascade,
				foreign key (`child`) references rights_items (`name`) on delete cascade on update cascade
			) engine InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'rights_assignment' => "create table rights_assignment (
				`itemname`             varchar(64) not null,
				`userid`               varchar(64) not null,
				`bizrule`              text,
				`data`                 text,
				primary key (`itemname`,`userid`),
				foreign key (`itemname`) references rights_items (`name`) on delete cascade on update cascade
			) engine InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'rights_protected' => "CREATE TABLE  rights_protected (
				`itemname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				primary key (`itemname`),
				foreign key (`itemname`) references rights_items (`name`) on delete cascade on update cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'question_set' => "create table question_set (
				`id` int(11) not null auto_increment,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`name` varchar(128) collate utf8_unicode_ci not null default '',
				`description` text default null collate utf8_unicode_ci,
				
				primary key (`id`),
				foreign key (`create_user`) references `user`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'question_set_history' => "create table question_set_history (
				`history_id` int(11) not null auto_increment,
				`id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`name` varchar(128) collate utf8_unicode_ci not null default '',
				`description` text default null collate utf8_unicode_ci,
				
				primary key (`history_id`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`id`) references `question_set`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'question_group' => "create table question_group (
				`id` int(11) not null auto_increment,
				`set_id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`name` varchar(128) collate utf8_unicode_ci not null default '',
				`description` text default null collate utf8_unicode_ci,
				`item_order` int(11)  not null,
				
				primary key (`id`),
				unique (`set_id`, `item_order`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`set_id`) references `question_set`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'question_group_history' => "create table question_group_history (
				`history_id` int(11) not null auto_increment,
				`id` int(11) not null,
				`set_id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`name` varchar(128) collate utf8_unicode_ci not null default '',
				`description` text default null collate utf8_unicode_ci,
				`item_order` int(11)  not null,
				
				primary key (`history_id`),
				foreign key (`id`) references `question_group`(`id`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`set_id`) references `question_set`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'question' => "create table question (
				`id` int(11) not null auto_increment,
				`group_id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`type` int(11) not null,
				`question` text collate utf8_unicode_ci default null,
				`description` text default null collate utf8_unicode_ci,
				`item_order` int(11)  not null,
				
				primary key (`id`),
				unique (`group_id`, `item_order`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`group_id`) references `question_group`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'question_history' => "create table question_history (
				`history_id` int(11) not null auto_increment,
				`id` int(11),
				`group_id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`type` int(11) not null,
				`question` text collate utf8_unicode_ci default null,
				`description` text default null collate utf8_unicode_ci,
				`item_order` int(11)  not null,
				
				primary key (`history_id`),
				foreign key (`id`) references `question`(`id`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`group_id`) references `question_group`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'answer' => "create table answer (
				`id` int(11) not null auto_increment,
				`question_id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`answer` text collate utf8_unicode_ci default null,
				`is_correct` boolean not null default false,
				`description` text default null collate utf8_unicode_ci,
				
				`item_order` int(11)  not null,
				
				primary key (`id`),
				unique (`question_id`, `item_order`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`question_id`) references `question`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'answer_history' => "create table answer_history (
				`history_id` int(11) not null auto_increment,
				`id` int(11) not null,
				`question_id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`enabled` boolean not null default true,
				
				`answer` text collate utf8_unicode_ci default null,
				`is_correct` boolean not null default false,
				`description` text default null collate utf8_unicode_ci,
				
				`item_order` int(11)  not null,
				
				primary key (`history_id`),
				foreign key (`id`) references `answer`(`id`),
				foreign key (`create_user`) references `user`(`id`),
				foreign key (`question_id`) references `question`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'user_group' => "create table user_group (
				`id` int(11) not null auto_increment,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				
				`name` varchar(512) collate utf8_unicode_ci not null default '',
				`description`  text default null collate utf8_unicode_ci,
				
				primary key(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'user_group_history' => "create table user_group_history (
				`history_id` int(11) not null auto_increment,
				`id` int(11) not null,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				
				`name` varchar(512) collate utf8_unicode_ci not null default '',
				`description`  text default null collate utf8_unicode_ci,
				
				primary key(`history_id`),
				foreign key(`id`) references `user_group`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'user_group_assignment' => "create table user_group_assignment (
				`user_id` int(11) not null,
				`group_id` int(11) not null,
				
				primary key(`user_id`, `group_id`),
				foreign key(`user_id`) references `user`(`id`),
				foreign key(`group_id`) references `user_group`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'test' => "create table test (
				`id` int(11) not null auto_increment,
				`create_date` int(11) not null,
				`create_user` int(11) not null,
				`last_update_date` int(11) not null,
				`last_update_user` int(11) not null,
				`is_deleted` boolean not null default false,
				`status` int(11) not null default 0,
				
				`name` varchar(512) collate utf8_unicode_ci not null default '',
				`description`  text default null collate utf8_unicode_ci,
				`begin_time` int(11) not null,
				`end_time` int(11) not null,
				`duration_time` int(11) not null,
				
				`question_set_id` int(11) not null,
				`question_set_version` int(11) not null,

				primary key(`id`),
				foreign key(`question_set_id`) references `question_set`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'test_user_group' => "create table test_user_group (
				`test_id` int(11) not null,
				`group_id` int(11) not null,

				primary key(`test_id`, `group_id`),
				foreign key(`test_id`) references `test`(`id`),
				foreign key(`group_id`) references `user_group`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'test_question_group' => "create table test_question_group (
				`test_id` int(11) not null,
				`group_id` int(11) not null,
				`question_types` int(11) not null default 0,
				`question_quantity` int(11) not null default 0,
				`answers` int(11) not null default 0,

				primary key(`test_id`, `group_id`),
				foreign key(`test_id`) references `test`(`id`),
				foreign key(`group_id`) references `question_group`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'test_user_log' => "create table test_user_log (
				`id` int(11) not null auto_increment,
				`test_id` int(11) not null,
				`user_id` int(11) not null,
				`status` int(11) not null,
				`create_date` int(11) not null,
				`last_change_date` int(11) not null,
				`end_date` int(11) not null,
				`mark` float default null,
				`passed` boolean default null,
				
				primary key(`id`),
				foreign key(`test_id`) references `test`(`id`),
				foreign key(`user_id`) references `user`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'test_user_question_log' => "create table test_user_question_log (
				`id` int(11) not null auto_increment,
				`test_user_id` int(11) not null,
				`question_id` int(11) not null,
				
				`last_change_date` int(11) default null,
				
				`score` double default null,

				primary key(`id`),
				foreign key(`test_user_id`) references `test_user_log`(`id`),
				foreign key(`question_id`) references `question`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
		
		'test_user_answer_log' => "create table test_user_answer_log (
				`id` int(11) not null auto_increment,
				`test_log_id` int(11) not null,
				`answer_id` int(11) not null,
				`display_order` int(11) not null,
				
				`selected` int(11) not null default -1,
				`item_order` int(11) not null default 1,
				
				`last_change_date` int(11) default null,
				
				primary key(`id`),
				foreign key(`test_log_id`) references `test_user_question_log`(`id`),
				foreign key(`answer_id`) references `answer`(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		",
	);
	
	
	private $rights_items = array(
//		array(
//			'name' => '',
//			'type' => 0,
//			'description' => '',
//			'bizrule' => '',
//			'data' => ''
//		),
		array(
			'name' => 'admin',
			'type' => 1,
			'description' => "Access to admin panel",
		),
		// rights
		array(
			'name' => 'rights',
			'type' => 0,
			'description' => "Base operation for rights module. Allows user to see module's menu item.",
		),
		array(
			'name' => 'rights.manage_items_relations',
			'type' => 0,
			'description' => "rights. permission to add/remove item's children",
		),
		array(
			'name' => 'rights.manage_user_assignments',
			'type' => 0,
			'description' => 'rights. permission to assign/revoke items to/from user',
		),
		array(
			'name' => 'rights.view_assingments',
			'type' => 0,
			'description' => 'rights. view list of users and rights assigned to them.'
		),
		array(
			'name' => 'rights.view_item_details',
			'type' => 0,
			'description' => 'rights. permission to see item details (childen and parents of item).',
		),
		array(
			'name' => 'rights.view_list',
			'type' => 0,
			'description' => 'rights. view list of operations/tasks/roles',
		),
		array(
			'name' => 'rights.view_user_assignments',
			'type' => 0,
			'description' => 'rights. permission to see which items are assigned do user.',
		),
		array(
			'name' => 'rights.create_item',
			'type' => 0,
			'description' => 'rights. permission to create items',
		),
		array(
			'name' => 'rights.delete_item',
			'type' => 0,
			'description' => 'rights. permission to delete items',
		),
		array(
			'name' => 'rights.update_item',
			'type' => 0,
			'description' => 'rights. permission to update name, description, bizrule and data of operation/task/role',
		),
		
		// users
		array(
			'name' => 'users',
			'type' => 0,
			'description' => "Base operation for users module. Allows user to see module's menu item.",
		),
		array(
			'name' => 'users.create_user',
			'type' => 0,
			'description' => 'users.create_user',
		),
		array(
			'name' => 'users.activate_user',
			'type' => 0,
			'description' => 'users.activate_user',
		),
		array(
			'name' => 'users.view_users_list',
			'type' => 0,
			'description' => 'users.view_users_list',
		),
		array(
			'name' => 'users.update_user',
			'type' => 0,
			'description' => "update any user's details"
		),
		array(
			'name' => 'users.update_self_details',
			'type' => 0,
			'description' => 'users.update_self_details',
		),
		array(
			'name' => 'users.update_activity',
			'type' => 0,
			'description' => 'users.update_activity',
		),
		array(
			'name' => 'users.view_details',
			'type' => 0,
			'description' => 'users. view details of any user',
		),
		array(
			'name' => 'users.view_self_details',
			'type' => 0,
			'description' => 'users. view only self details',
		),
		
		// settings
		array(
			'name' => 'settings',
			'type' => 0,
			'description' => "Base operation for settings module. Allows user to see module's menu item.",
		),
	);
	
	
	private $rights_item_child = array(
//		'item' => 'child'
		// rights
		'rights.view_user_assignments' => 'rights',
		'rights.view_assingments' => 'rights',
		'rights.view_list' => 'rights',
		'rights.create_item' => 'rights',
		'rights.delete_item' => 'rights',
		'rights.update_item' => 'rights',
		'rights.view_item_details' => 'rights',
		'rights.view_item_details' => 'rights.view_list',
		'rights.update_item' => 'rights.view_list',
		'rights.delete_item' => 'rights.view_list',
		'rights.manage_items_relations' => 'rights.view_item_details',
		'rights.manage_user_assignments' => 'rights.view_user_assignments',
		'rights.view_assingments' => 'rights.view_user_assignments',
		
		// users
		'users.create_user' => 'users',
		'users.activate_user' => 'users',
		'users.view_users_list' => 'users',
		'users.update_user' => 'users',
		'users.update_activity' => 'users',
		'users.view_details' => 'users',
		'users.view_details' => 'users.view_self_details',
	);
	
	
	private $settings = array(
//		array(
//			'category' => '',
//			'name' => '',
//			'value' => ''
//		),
		array(
			'category' => 'appAdmin',
			'name' => 'paginationPageSize',
			'value' => 25
		),
		array(
			'category' => 'appAdmin',
			'name' => 'applicationName',
			'value' => 'Egzamin',
		),
		array(
			'category' => 'appAdmin',
			'name' => 'sessionTime',
			'value' => 15,
		),
	);
}
