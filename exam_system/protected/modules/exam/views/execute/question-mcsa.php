<div>
	<table>
		<tr>
			<td style="width: 20px;">
				<p><?php echo $counter; ?></p>
			</td>
			<td colspan="2">
				<?php echo $questionLog->question->question; ?>
			</td>
			<td>
				<a href="#" class="clear">wyczyść odpowiedź</a>
			</td>
		</tr>
		<?php foreach($questionLog->testUserAnswerLogs as $answerLog) : ?>
		<tr>
			<td></td>
			<td style="width: 20px;">
				<input type="radio" name="question-<?php echo $questionLog->id; ?>" <?php if($answerLog->selected==1) echo "checked"; ?> value="<?php echo $answerLog->id; ?>">
			</td>
			<td>
				<?php echo $answerLog->answer->answer; ?>
			</td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>