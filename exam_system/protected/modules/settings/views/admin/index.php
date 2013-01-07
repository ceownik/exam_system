<div style="width: 100%; overflow: auto;" class="settings-page">

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

	<?php foreach($names as $name) : 
		$i = $name[0].'_'.$name[1]; ?>
		<div class="row">
			<?php echo CHtml::label($name[2], $i); ?>
			<?php echo CHtml::textField($i, $items[$i]); ?>
			<?php if(!empty($errors[$i])) : ?>
				<?php echo implode(', ', $errors[$i]); ?>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
		

	<div class="row buttons">
		<?php echo CHtml::submitButton( 'Zapisz'  ); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><?php 