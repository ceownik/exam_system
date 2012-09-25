<?php

echo 'a';
die;
$start = microtime();


// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

$application = Yii::createWebApplication($config);






// my global helper function
function pre_dump($data, $die=false)
{
	echo '<div style="
			display: block;
			width: 90%;
			overflow: auto;
			border: 2px solid #dd3333;
			padding: 5px;
		"><pre>';
	print_r($data);
	echo '</pre></div>';
	if($die)
		die;
}


function dump($var, $die=false, $higlight = true, $depth = 100)
{
	echo '<div style="
			display: block;
			width: 98%;
			overflow: auto;
			border: 2px solid #dd3333;
			padding: 5px;
			background-color: #fff;
			margin: 2px auto;
		"><pre>';
	CVarDumper::dump($var, $depth, $higlight);
	echo '</pre></div>';
	if($die)
		die;
}




$application->run();

$end = microtime();

$time = $end - $start;

//dump($application);
$application->components['db']->createCommand()
		->insert('kcms_performance_log', array(
			'date' => time(),
			'request_uri' => $application->components['request']->requestUri,
			'render_time' => ($time < 0) ? 0 : $time,
		));
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