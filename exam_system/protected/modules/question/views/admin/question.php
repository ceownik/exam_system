<?php 
switch($question->type) {
	case Question::TYPE_MCSA: 
		$this->renderPartial('question-mcsa', array(
			'question' => $question,
			'questionCount' => $questionCount,
		));
		break;
}
?>