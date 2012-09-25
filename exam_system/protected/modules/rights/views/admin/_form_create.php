<div class="rights-form">

	<?php
	$form = $this->beginWidget( 'CActiveForm', array(
		'id' => 'rights-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'htmlOptions' => array(
			'class' => 'rights-form color-rows active-form',
		),
		'errorMessageCssClass' => 'errorMessage msg error',
			) );
	?>

	

	<?php echo $form->errorSummary( $model ); ?>

	<div class="row">
		<?php echo $form->label( $model, 'name' ); ?>
		<?php echo $form->textField( $model, 'name', array( 'maxlength' => 64, 'disabled' => ($model->isProtected() && Yii::app()->user->id!='1') ? true : false ) ); ?>
		<?php echo $form->error( $model, 'name' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'description' ); ?>
		<?php echo $form->textField( $model, 'description', array(  ) ); ?>
		<?php echo $form->error( $model, 'description' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'bizrule' ); ?>
		<?php echo $form->textField( $model, 'bizrule', array(  ) ); ?>
		<?php echo $form->error( $model, 'bizrule' ); ?>
		<span class="success"></span>
	</div>

	<div class="row">
		<?php echo $form->label( $model, 'data' ); ?>
		<?php echo $form->textField( $model, 'data', array(  ) ); ?>
		<?php echo $form->error( $model, 'data' ); ?>
		<span class="success"></span>
	</div>

	<?php // create as protected (only for admin user)
	
	?>

	<div class="row buttons">
		<?php echo CHtml::button('Cancel', array ( 'submit' => array('index') ) ); ?>
		<?php echo CHtml::submitButton( $model->isNewRecord ? 'Create' : 'Save'  ); ?>
	</div>

	<?php $this->endWidget(); ?>

	<?php ?>
</div><!-- form -->