<div class="question-row <?php echo ($counter%2==0)?'even':'odd'; ?> "><?php

switch($question->type) {
	case Question::TYPE_MCSA:
		$this->renderPartial('print-question-mcsa', array(
			'question'=>$question,
			'answers'=>$answers,
			'counter'=>$counter,
		));
		break;
	case Question::TYPE_MCMA:
		$this->renderPartial('print-question-mcsa', array(
			'question'=>$question,
			'answers'=>$answers,
			'counter'=>$counter,
		));
		break;
	default:
		echo 'error';
		break;
};

?></div>