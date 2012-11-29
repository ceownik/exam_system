<div class="score-test">
	<form id="test-form" method="POST">
	<?php
	$counter = 0;
	foreach($questions as $k => $question) {
		++$counter; ?>
		<div class="question">
			<div class="question-body">
				<div class="question-question">
					<?php echo $question->question; ?>
				</div>
				<div class="answer">
					<?php echo $answers[$k]->answer; ?>
				</div>			
			</div>
			<div class="score">
				<input name="question[<?php echo $k; ?>]score" value="<?php echo $scores[$k]; ?>">
			</div>
		</div>
		<?php
	} ?>
	<div class="row buttons">
		<?php echo CHtml::button('Anuluj', array('submit'=>Yii::app()->createUrl('/admin/exam/testSummary/id/'.$model->test->id))); ?>
		<?php echo CHtml::submitButton('Zapisz'); ?>
	</div>
	</form>
</div>