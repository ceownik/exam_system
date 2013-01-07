<div class="score-test">
	<form id="test-form" method="POST">
	<?php
	$counter = 0;
	foreach($questions as $k => $question) {
		++$counter; ?>
		<div class="question">
			<div class="score">
				Ilość punktów: <input name="question[<?php echo $k; ?>]score" value="<?php echo $scores[$k]; ?>" style="width: 20px; padding: 0px 3px; border: 1px solid #ccc; text-align: center;">
			</div>
			<div class="question-body">
				<div class="question-question">
					<?php echo $question->question; ?>
				</div>
				<div class="answer-box">
					<?php if(isset($answers[$k])) { 
						foreach($answers[$k] as $answer) {
							?><div class="dot <?php if($answer->is_correct) echo 'green'; else echo 'grey'; ?>" title="odpowiedź <?php if($answer->is_correct) echo 'poprawna'; else echo 'błędna'; ?>"></div><?php
							if($answer->selected==1 && $answer->is_correct==1) {
								echo '<div class="answer selected correct">'.$answer->answer.'</div>';
							} elseif($answer->selected==1 && $answer->is_correct==0) {
								echo '<div class="answer selected wrong">'.$answer->answer.'</div>';
							} else {
								echo '<div class="answer">'.$answer->answer.'</div>';
							}
						}
					} else { 
						echo "Nie udzielono odpowiedzi."; 
					
					} ?>
				</div>			
			</div>
		</div>
		<?php
	} ?>
	<div class="mark-row">
		<div>
			Zdobyte punkty: <?php echo $sum.' / '.$total .' ('.round(($sum/$total)*100, 2).'%)'; ?>
		</div>
		<div>
			Ocena:  <input name="mark" value="<?php echo $model->mark; ?>" style="width: 20px; padding: 0px 3px; border: 1px solid #ccc; text-align: center;">
		</div>
		<div>
			Test zaliczony: <input name="passed" <?php echo $model->passed==1 ? 'checked' : ''; ?> type="checkbox">
		</div>
	</div>
	<div class="row buttons">
		<?php echo CHtml::button('Anuluj', array('submit'=>Yii::app()->createUrl('/admin/exam/testSummary/id/'.$model->test->id))); ?>
		<?php echo CHtml::submitButton('Zapisz'); ?>
	</div>
	</form>
</div>