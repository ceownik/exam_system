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
				Ilość punktów: <input name="question[<?php echo $k; ?>]score" value="<?php echo $scores[$k]; ?>" style="width: 20px; padding: 0px 3px; border: 1px solid #ccc; text-align: center;">
			</div>
		</div>
		<?php
	} ?>
	<div class="mark-row">
		<div>
			Ocena:  <input name="mark" value="<?php echo $model->mark; ?>" style="width: 20px; padding: 0px 3px; border: 1px solid #ccc; text-align: center;">
		</div>
		<div>
			Test zaliczony: <input name="passed" value="<?php echo $model->passed; ?>" type="checkbox">
		</div>
	</div>
	<div class="row buttons">
		<?php echo CHtml::button('Anuluj', array('submit'=>Yii::app()->createUrl('/admin/exam/testSummary/id/'.$model->test->id))); ?>
		<?php echo CHtml::submitButton('Zapisz'); ?>
	</div>
	</form>
</div>