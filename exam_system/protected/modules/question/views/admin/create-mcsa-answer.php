<div class="answer-form">

	<?php
	$form = $this->beginWidget( 'CActiveForm', array(
		'id' => 'answer-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'htmlOptions' => array(
			'class' => 'rights-form color-rows active-form',
		),
		'errorMessageCssClass' => 'errorMessage msg error',
			) );
	?>
	
	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->label($model, 'answer'); ?>
		<?php echo $form->textField($model, 'answer', array('id'=>'answer-editor')); ?>
		<?php echo $form->error($model, 'answer'); ?>
		<span class="success"></span>
	</div>
	
	<?php $options = array('0'=>'No');
	if(!$question->hasCorrectAnswer) { $options['1'] = 'Yes'; }?>
	<div class="row">
		<?php echo $form->label($model, 'is_correct'); ?>
		<?php echo $form->radioButtonList($model, 'is_correct', $options); ?>
		<?php echo $form->error($model, 'is_correct'); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', array('id'=>'description-editor')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<span class="success"></span>
	</div>


	<div class="row buttons">
		<?php echo CHtml::button('Cancel', array ( 'submit' => array('/admin/question/viewQuestionSet/id/'.$question->group->set_id))); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->

<?php Yii::app()->clientScript->registerScript('editor', 'bindTinyMce("answer-editor, description-editor")'); ?>