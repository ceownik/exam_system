<div class="user-form">

	<?php
	$form = $this->beginWidget( 'CActiveForm', array(
		'id' => 'user-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'htmlOptions' => array(
			'class' => 'user-form color-rows active-form',
		),
		'errorMessageCssClass' => 'errorMessage msg error',
			) );
	?>

	

	<?php echo $form->errorSummary( $model ); ?>

	<div class="row">
		<?php echo $form->label( $model, 'display_name' ); ?>
		<?php echo $form->textField( $model, 'display_name', array( 'maxlength' => 128 ) ); ?>
		<?php echo $form->error( $model, 'display_name' ); ?>
		<span class="success"></span>
	</div>

	<?php if(Yii::app()->user->checkAccess('users.activate_user')) : ?>
	<div class="row checkbox">
		<?php echo $form->label( $model, 'is_active' ); ?>
		<?php echo $form->checkBox( $model, 'is_active' ); ?>
		<?php echo $form->error( $model, 'is_active' ); ?>
	</div>
	<?php endif; ?>

	<?php if(Yii::app()->user->checkAccess('users.update_activity')) : ?>
	<div class="row radio inline">
		<?php echo $form->label( $model, 'active_from_now' ); ?>
		<?php echo $form->radioButtonList( 
				$model, 
				'active_from_now', 
				array( 
					1 => 'now', 
					0 => 'specific date' 
				), 
				array( 
					'template' => '<p>{input}{label}</p>',
					'separator' => '',
				) 
			); ?>
		<?php echo $form->error( $model, 'active_from_now' ); ?>
	</div>

	
	<div class="row">
		<?php echo $form->label( $model, 'active_from_date' ); ?>
		<?php echo $form->textField( $model, 'active_from_date', array('class'=>'datepicker') ); ?>
		<?php echo $form->error( $model, 'active_from_date' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'active_to_date' ); ?>
		<?php echo $form->textField( $model, 'active_to_date', array('class'=>'datepicker') ); ?>
		<?php echo $form->error( $model, 'active_to_date' ); ?>
		<span class="success"></span>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton( $model->isNewRecord ? 'Create' : 'Save'  ); ?>
	</div>

	<?php $this->endWidget(); ?>

	<?php ?>
</div><!-- form -->