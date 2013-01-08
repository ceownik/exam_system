<div id="test">
	<div class="hash top"><?php echo $hash; ?></div>
	<h2 class="test-title"><?php echo $test->name; ?></h2>

	<?php
	$counter = 0;
	foreach($questions as $question) {
		$this->renderPartial('print-question', array(
			'question'=>$question,
			'answers'=>$answers[$question->primaryKey],
			'counter'=>++$counter,
		));
	}
	?>
</div>

<div id="answers">
	<div class="hash"><?php echo $hash; ?></div>
	<h2 class="test-title"><?php echo $test->name; ?> - poprawne odpowiedzi</h2>
	<?php 
	$counter = 0;
	foreach($questions as $question) {
		++$counter;
		$_answers = $answers[$question->primaryKey];
		$a = $this->getAlphabet(); 
		?>
		<div class="question-mcsa ">
			<table>
				<tr>
					<td style="width: 20px;">
						<p><?php echo $counter; ?>.</p>
					</td>
					<td colspan="2">
						<?php echo $question->question; ?>
					</td>
				</tr>
				<?php $i=0; foreach($_answers as $answer) : ?>
				<?php if($answer->is_correct) : ?>
					<tr>
						<td></td>
						<td style="width: 20px;">
							<?php echo $a[$i++]; ?>).
						</td>
						<td>
							<?php echo $answer->answer; ?>
						</td>
					</tr>
				<?php else : 
					$i++; 
				endif; ?>
				<?php endforeach; ?>
			</table>
		</div>	
		<?
	}
	?>
</div>