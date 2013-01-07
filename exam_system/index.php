<?php
error_reporting(E_ALL);

ini_set('display_errors', '1');
		
$start = microtime(true);

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


defined('APP_BASE') or define('APP_BASE', dirname(__FILE__));


$yii=dirname(__FILE__).'/yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

$installationDir = dirname(__FILE__).'/protected/installation';
$installationConfig = dirname(__FILE__).'/protected/installation/config/installation.php';


require_once($yii);


// search for installation dir
if(is_dir($installationDir))
{
	// run installation process
	$application = Yii::createWebApplication($installationConfig);
}
else
{
	// run normal application
	$application = Yii::createWebApplication($config);
}


$application->run();




$end = microtime(true);

$time = $end - $start;

?>