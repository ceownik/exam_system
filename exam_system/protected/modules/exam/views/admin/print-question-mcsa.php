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
		<?php $i=0; $a = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','w','y','z'); foreach($answers as $answer) : ?>
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