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
		<?php echo $form->label( $model, 'old_password' ); ?>
		<?php echo $form->passwordField( $model, 'old_password', array( 'maxlength' => 64 ) ); ?>
		<?php echo $form->error( $model, 'old_password' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'new_password' ); ?>
		<?php echo $form->passwordField( $model, 'new_password', array( 'maxlength' => 64 ) ); ?>
		<?php echo $form->error( $model, 'new_password' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'password_repeat' ); ?>
		<?php echo $form->passwordField( $model, 'password_repeat', array( 'maxlength' => 64 ) ); ?>
		<?php echo $form->error( $model, 'password_repeat' ); ?>
		<span class="success"></span>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Zapisz', array('confirm'=>'Zmienić hasło?')  ); ?>
	</div>

	<?php $this->endWidget(); ?>

	<?php ?>
</div><!-- form -->