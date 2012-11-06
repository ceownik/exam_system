<div class="question-form">

	<?php
	$form = $this->beginWidget( 'CActiveForm', array(
		'id' => 'question-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'htmlOptions' => array(
			'class' => 'rights-form color-rows active-form',
		),
		'errorMessageCssClass' => 'errorMessage msg error',
			) );
	?>
	
	<div class="row">
		<?php echo $form->label($model, 'question'); ?>
		<?php echo $form->textField($model, 'question', array('id'=>'question-editor')); ?>
		<?php echo $form->error($model, 'question'); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', array('id'=>'description-editor')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<span class="success"></span>
	</div>


	<div class="row buttons">
		<?php echo CHtml::button('Cancel', array ( 'submit' => array('/admin/question/viewQuestionSet/id/'.$model->group->set_id))); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->

<?php Yii::app()->clientScript->registerScript('editor', 'bindTinyMce("question-editor, description-editor")'); ?>