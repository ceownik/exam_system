
<div class="test-form">
	
	<?php
	$form = $this->beginWidget( 'CActiveForm', array(
		'id' => 'test-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'htmlOptions' => array(
			'class' => 'color-rows active-form',
		),
		'errorMessageCssClass' => 'errorMessage msg error',
			) );
	?>

	<?php echo $form->errorSummary($model); ?>
	
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

	<div class="row">
		<?php echo $form->label( $model, 'beginTime' ); ?>
		<?php echo $form->textField( $model, 'beginTime', array('class'=>'datepicker') ); ?>
		<?php echo $form->error( $model, 'beginTime' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'endTime' ); ?>
		<?php echo $form->textField( $model, 'endTime', array('class'=>'datepicker') ); ?>
		<?php echo $form->error( $model, 'endTime' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'duration_time' ); ?>
		<?php echo $form->textField( $model, 'duration_time'); ?>
		<?php echo $form->error( $model, 'duration_time' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'question_set_id' ); ?>
		<?php echo $form->dropDownList( $model, 'question_set_id', CHtml::listData(QuestionSet::model()->findEnabled(), 'id', 'name') ); ?>
		<?php echo $form->error( $model, 'question_set_id' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'groupsIds' ); ?>
		<?php echo $form->checkBoxList( $model, 'groupsIds', CHtml::listData(UserGroup::model()->findAll(), 'id', 'name') ); ?>
		<?php echo $form->error( $model, 'groupsIds' ); ?>
		<span class="success"></span>
	</div>

	<div class="row buttons">
		<?php echo Chtml::button('Cancel', array('submit'=>Yii::app()->createUrl('/admin/exam'))); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<?php Yii::app()->clientScript->registerScript('editor', 'bindTinyMce("description-editor")'); ?>