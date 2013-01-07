<div id="test">
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