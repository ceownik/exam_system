<?php Yii::app()->clientScript->registerScript('update-quantity', 'updateQuestionQuantity("'.$model->id.'", ".question-type", "'.Yii::app()->createAbsoluteUrl('').'");'); ?>

<div class="test-form">
	
	<?php if($model->question_set_version != $model->questionSet->last_update_date): ?>
	<div>uaktualnij</div>
	<?php endif; ?>
	
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
	
	<?php foreach($model->testQuestionGroups as $group) : ?>
	<div class="group" id="group_id<?php echo $group->group_id; ?>">
		<h2>grupa pytań: <?php echo $group->questionGroup->name; ?></h2>

		<div class="row">
			<?php echo $form->label($group, '['.$group->group_id.']question_types'); ?>
			<?php echo $form->dropDownList($group, '['.$group->group_id.']question_types', Question::getTypesOptions(), array('prompt'=>'All', 'class'=>'question-type')); ?>
			<?php echo $form->error($group, '['.$group->group_id.']question_types'); ?>
			<span class="success"></span>
		</div>

		<div class="row">
			<?php echo $form->label($group, '['.$group->group_id.']question_quantity'); ?>
			<?php echo $form->dropDownList($group, '['.$group->group_id.']question_quantity', array(), array('class'=>'question-quantity')); ?>
			<?php echo $form->error($group, '['.$group->group_id.']question_quantity'); ?>
			<span class="success"></span>
		</div>
	</div>
	<?php endforeach; ?>
	

	<div class="row buttons">
		<?php echo Chtml::button('Cancel', array('submit'=>Yii::app()->createUrl('/admin/exam'))); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<?php Yii::app()->clientScript->registerScript('editor', 'bindTinyMce("description-editor")'); ?>