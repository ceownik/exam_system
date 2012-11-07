<div class="question-set-wrapper">
	<div class="content-submenu" style="text-align: right; float: right">
		<?php echo CHtml::button('update set', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionSet/id/'.$model->primaryKey.'/type/1'), 'class'=>'submenu button')); ?>
		<?php echo CHtml::button('Add question group', array('submit'=>Yii::app()->createUrl('question/admin/createQuestionGroup/set_id/'.$model->primaryKey), 'class'=>'submenu button')) ?>
	</div>

	<h2 class="question-set-title"><?php echo $model->name; ?></h2>

	<div class="separator"></div>

	<div class="question-set-description"><?php echo $model->description; ?></div>

		<?php foreach($model->questionGroups as $key => $group) : ?>
		<div class="question-group-wrapper">
			<div class="content-submenu" style="text-align: right; float: right">
				<?php echo CHtml::button('update group', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionGroup/id/'.$group->primaryKey.'/type/1'), 'class'=>'submenu button')); ?>
				<?php echo CHtml::button('remove group', array('submit'=>Yii::app()->createUrl('question/admin/removeQuestionGroup/id/'.$group->primaryKey.'/type/1'), 'confirm'=>'Czy na pewno usunąć tę grupę pytań?', 'class'=>'submenu button')); ?>
				<?php echo CHtml::button('Add question', array('submit'=>Yii::app()->createUrl('question/admin/createQuestion/group_id/'.$group->primaryKey), 'class'=>'submenu button')) ?>
			</div>
			
			<h3 class="question-group-title">grupa pytań: <?php echo $group->name; ?></h3>

			<div class="separator"></div>

			<div><?php echo $group->description; ?></div>

			<div class="questions-wrapper">
				<?php if(count($group->questions)>0):
					$questionCount = 0;
					foreach($group->questions as $question): 
						$questionCount++; 
						$this->renderPartial('question', array(
							'questionCount' => $questionCount,
							'question' => $question
						));
					endforeach;
				else: ?>
					Brak pytań w tej grupie
				<?php endif; ?>
			</div>
				
		</div>
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
</div>
