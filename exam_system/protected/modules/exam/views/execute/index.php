<?php Yii::app()->clientScript->registerScript('test', 'performTest("'.Yii::app()->createAbsoluteUrl('').'", '.$testUserLog->id.', '.($testUserLog->end_date - time()).', '.($testUserLog->end_date - $testUserLog->create_date).');'); ?>
<div class="message-wrapper msg notice"></div>
<div class="timer-wrapper msg ">Pozostały czas: <span class="time">00:00:00</span></div>
<a href="#" class="save-icon"><img src="/images/Save.png" alt="Zapisz" /></a>
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