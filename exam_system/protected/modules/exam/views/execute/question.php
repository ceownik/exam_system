<div class="question-row <?php echo ($counter%2==0)?'even':'odd'; ?> "><?php

switch($questionLog->question->type) {
	case Question::TYPE_MCSA:
		$this->renderPartial('question-mcsa', array(
			'questionLog'=>$questionLog,
			'counter'=>$counter,
		));
		break;
	default:
		echo 'error';
		break;
};

?></div>