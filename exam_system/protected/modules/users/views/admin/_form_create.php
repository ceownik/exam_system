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
		<?php echo $form->label( $model, 'login' ); ?>
		<?php echo $form->textField( $model, 'login', array( 'maxlength' => 128 ) ); ?>
		<?php echo $form->error( $model, 'login' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'display_name' ); ?>
		<?php echo $form->textField( $model, 'display_name', array( 'maxlength' => 128 ) ); ?>
		<?php echo $form->error( $model, 'display_name' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'email' ); ?>
		<?php echo $form->textField( $model, 'email', array( 'maxlength' => 128 ) ); ?>
		<?php echo $form->error( $model, 'email' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'password' ); ?>
		<?php echo $form->passwordField( $model, 'password', array( 'maxlength' => 64 ) ); ?>
		<?php echo $form->error( $model, 'password' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'password_repeat' ); ?>
		<?php echo $form->passwordField( $model, 'password_repeat', array( 'maxlength' => 64 ) ); ?>
		<?php echo $form->error( $model, 'password_repeat' ); ?>
		<span class="success"></span>
	</div>

	<?php if(Yii::app()->user->checkAccess('users.activate_user')) : ?>
	<div class="row checkbox">
		<?php echo $form->label( $model, 'is_active' ); ?>
		<?php echo $form->checkBox( $model, 'is_active' ); ?>
		<?php echo $form->error( $model, 'is_active' ); ?>
	</div>
	<?php endif; ?>

	<div class="row radio inline">
		<?php echo $form->label( $model, 'active_from_now' ); ?>
		<?php echo $form->radioButtonList( 
				$model, 
				'active_from_now', 
				array( 
					1 => 'Chwili utworzenia', 
					0 => 'Konkretnej daty (zdefiniowanej poniżej)' 
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

	<?php /*<div class="row">
		<?php echo $form->label( $model, 'active_from_time' ); ?>
		<?php $active_from_time_value = ( (isset( $model->active_from_time )) ? $model->active_from_time : '00:00') ; ?>
		<?php echo $form->textField( $model, 'active_from_time', array( 'value' => $active_from_time_value ) );?>
		<?php echo $form->error( $model, 'active_from_time' ); ?>
		<span class="success"></span>
	</div> */ ?>

	<div class="row">
		<?php echo $form->label( $model, 'active_to_date' ); ?>
		<?php echo $form->textField( $model, 'active_to_date', array('class'=>'datepicker') ); ?>
		<?php echo $form->error( $model, 'active_to_date' ); ?>
		<span class="success"></span>
	</div>

	<?php /*<div class="row">
		<?php echo $form->label( $model, 'active_to_time' ); ?>
		<?php echo $form->textField( $model, 'active_to_time', array( ) ); ?>
		<?php echo $form->error( $model, 'active_to_time' ); ?>
		<span class="success"></span>
	</div> */ ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton( $model->isNewRecord ? 'Utwórz' : 'Zapisz'  ); ?>
	</div>

	<?php $this->endWidget(); ?>

	<?php ?>
</div><!-- form -->