<?php

$start = microtime();

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




$end = microtime();

$time = $end - $start;

?>

<?php 
 // TODO: delete lines below
?>
<div id="render-time" 
	 style="background-color: rgb(15,0,80); 
		color: rgb(0,215,75); 
		padding: 0pc 5px; 
		position: fixed; 
		bottom: 0px; 
		right: 0px; 
		font-weight: bold;
		cursor: pointer;">
	<?php echo 'Render time: '.$time; ?>
</div>
<script type="text/javascript">
	console.log('Render time: <?php echo $time; ?>');
	(function($){
		$("#render-time").click(function(){
			$(this).fadeOut();
		});
	})(jQuery);
</script><?