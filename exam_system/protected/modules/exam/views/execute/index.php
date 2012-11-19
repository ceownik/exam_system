<?php Yii::app()->clientScript->registerScript('test', 'performTest("'.Yii::app()->createAbsoluteUrl('').'", '.$testUserLog->id.');'); ?>
<div class="message-wrapper"></div>
<form id="test-form">
<?php
$counter = 0;
foreach($testUserLog->testUserQuestionLogs as $questionLog) {
	$this->renderPartial('question', array(
		'questionLog'=>$questionLog,
		'counter'=>++$counter,
	));
}
?>
</form>