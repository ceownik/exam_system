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
		<?php $i=0; $a = $this->getAlphabet(); foreach($answers as $answer) : ?>
		<tr>
			<td></td>
			<td style="width: 20px;">
				<?php echo $a[$i++]; ?>).
			</td>
			<td>
				<?php echo $answer->answer; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>