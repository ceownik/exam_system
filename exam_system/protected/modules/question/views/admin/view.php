<fieldset class="question-set">
	<legend>Question set: <?php echo $model->name; ?></legend>
	<div class="content-submenu" style="text-align: right;">
		<?php echo CHtml::button('update', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionSet/id/'.$model->primaryKey.'/type/1'))); ?>
		<?php echo CHtml::button('Add question group', array('submit'=>Yii::app()->createUrl('question/admin/createQuestionGroup/set_id/'.$model->primaryKey))) ?>
	</div>
	<div><?php echo $model->description; ?></div>
	
	<?php foreach($model->questionGroups as $key => $group) : ?>
	
	<fieldset class="question-group <?php echo $key; ?>">
		<legend>question group: <?php echo $group->name; ?></legend>
		
		<div class="content-submenu" style="text-align: right;">
			<?php echo CHtml::button('update', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionGroup/id/'.$group->primaryKey.'/type/1'))); ?>
			<?php //echo CHtml::button('Add question group', array('submit'=>Yii::app()->createUrl('question/admin/createQuestionGroup/set_id/'.$model->primaryKey))) ?>
		</div>
		
		<div><?php echo $group->description; ?></div>
		
		<fieldset>
			<legend>questions:</legend>
		</fieldset>
	</fieldset>
	
	<?php endforeach; ?>
</fieldset>



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