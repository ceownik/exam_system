<div class="content-submenu" style="text-align: right; float: right">
	<?php echo CHtml::button('update set', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionSet/id/'.$model->primaryKey.'/type/1'))); ?>
	<?php echo CHtml::button('Add question group', array('submit'=>Yii::app()->createUrl('question/admin/createQuestionGroup/set_id/'.$model->primaryKey))) ?>
</div>

<h2><?php echo $model->name; ?></h2>

<div><?php echo $model->description; ?></div>
	
	<?php foreach($model->questionGroups as $key => $group) : ?>
	
	<fieldset class="question-group <?php echo $key; ?>">
		<legend>question group: <?php echo $group->name; ?></legend>
		
		<div class="content-submenu" style="text-align: right;">
			<?php echo CHtml::button('update group', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionGroup/id/'.$group->primaryKey.'/type/1'))); ?>
			<?php echo CHtml::button('remove group', array('submit'=>Yii::app()->createUrl('question/admin/removeQuestionGroup/id/'.$group->primaryKey.'/type/1'), 'confirm'=>'Czy na pewno usunąć tę grupę pytań?')); ?>
			<?php echo CHtml::button('Add question', array('submit'=>Yii::app()->createUrl('question/admin/createQuestion/group_id/'.$group->primaryKey))) ?>
		</div>
		
		<div><?php echo $group->description; ?></div>
		
		<fieldset>
			<legend>questions:</legend>
			<?php $questionCount = 0;
			foreach($group->questions as $question): ?>
				<?php $questionCount++; 
				$this->renderPartial('question', array(
					'questionCount' => $questionCount,
					'question' => $question
				)) ?>
			<?php endforeach; ?>
		</fieldset>
	</fieldset>
	
	<?php endforeach; ?>




<?php

$this->widget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'my-dialog',
    'options'=>array(
        'title'=>false,
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>600,	
    ),
));

?>