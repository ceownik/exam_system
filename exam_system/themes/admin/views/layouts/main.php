<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
	<meta name="robots" content="noindex,nofollow" />
	
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.js"></script>
	
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/reset.css" /> <!-- RESET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" /> <!-- MAIN STYLE SHEET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->
	<link rel="alternate stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->
	<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.ui.all.css" /> 
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" /> <!-- GRAPHIC THEME -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template.css" /> <!-- WRITE YOUR CSS CODE HERE -->
	
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/ui/jquery-ui-1.8.22.custom.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/switcher.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/toggle.js"></script>
	
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/style.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/common.js"></script>
	
	<script type="text/javascript">
	;(function($){
		$(document).ready(function(){
			$(".tabs > ul").tabs();
		});
	})(jQuery);
	</script>
</head>

<body>

<div id="main">

	<?php echo $content; ?>

	<!-- Footer -->
	<div id="footer" class="box">

		<p class="f-left">&copy; 2012 Kamil Kafara &reg;</p>

		<p class="f-right"></p>

	</div> <!-- /footer -->

</div> <!-- /main -->

</body>
</html>