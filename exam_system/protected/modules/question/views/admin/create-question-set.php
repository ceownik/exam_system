<div class="question-set-form">

	<?php
	$form = $this->beginWidget( 'CActiveForm', array(
		'id' => 'question-set-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'htmlOptions' => array(
			'class' => 'rights-form color-rows active-form',
		),
		'errorMessageCssClass' => 'errorMessage msg error',
			) );
	?>
	
	<?php //echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->label($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('maxlength' => 128,)); ?>
		<?php echo $form->error($model, 'name'); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', array('id'=>'description-editor')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<span class="success"></span>
	</div>


	<div class="row buttons">
		<?php echo CHtml::button('Anuluj', array ( 'submit' => array($cancelUrl))); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Utwórz' : 'Zapisz'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->

<?php Yii::app()->clientScript->registerScript('editor', 'bindTinyMce("description-editor")'); ?>