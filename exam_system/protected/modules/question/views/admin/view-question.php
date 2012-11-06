<?php 
switch($model->type) {
	case Question::TYPE_MCSA: 
		$this->renderPartial('view-question-mcsa', array(
			'question' => $model
		));
		break;
}
?>