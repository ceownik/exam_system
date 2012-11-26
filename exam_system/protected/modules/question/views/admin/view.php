<?php Yii::app()->clientScript->registerScript('menu', 'questionShowDescription();'); ?>
<?php Yii::app()->clientScript->registerScript('descriptions', 'questionSetMenu();'); ?>


<div class="question-set-wrapper">
	<div class="content-submenu" style="text-align: right; float: right">
		<?php echo CHtml::button('menu zestawu', array('submit'=>'#', 'class'=>'parent button')); ?>
		<div class="content-submenu-sub">
			<?php echo CHtml::button('edytuj', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionSet/id/'.$model->primaryKey.'/type/1'), 'class'=>'submenu button')); ?>
			<?php echo $model->enabled ? 
					CHtml::button('zablokuj zestaw', array('submit'=>Yii::app()->createUrl('question/admin/disable/id/'.$model->primaryKey.'/type/set'), 'class'=>'submenu button')) :
					CHtml::button('odblokuj zestaw', array('submit'=>Yii::app()->createUrl('question/admin/enable/id/'.$model->primaryKey.'/type/set'), 'class'=>'submenu button'))?>
			<?php echo CHtml::button('dodaj grupę pytań', array('submit'=>Yii::app()->createUrl('question/admin/createQuestionGroup/set_id/'.$model->primaryKey), 'class'=>'submenu button')) ?>
		</div>
	</div>

	<h2 class="question-set-title"><?php echo $model->name; ?></h2>

	<div class="separator"></div>

	<div class="question-set-description"><?php echo $model->description; ?></div>

		<?php foreach($model->questionGroups as $key => $group) : ?>
		<div class="question-group-wrapper <?php if(!$group->enabled) echo 'disabled '; ?>">
			<div class="content-submenu" style="text-align: right; float: right">
				<?php echo CHtml::button('menu grupy', array('submit'=>'#', 'class'=>'parent button')); ?>
				<div class="content-submenu-sub">
					<?php echo CHtml::button('edytuj', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestionGroup/id/'.$group->primaryKey.'/type/1'), 'class'=>'submenu button')); ?>
					<?php echo $group->enabled ? 
						CHtml::button('zablokuj grupę', array('submit'=>Yii::app()->createUrl('question/admin/disable/id/'.$model->primaryKey.'/type/group'), 'class'=>'submenu button')) :
						CHtml::button('odblokuj grupę', array('submit'=>Yii::app()->createUrl('question/admin/enable/id/'.$model->primaryKey.'/type/group'), 'class'=>'submenu button'))?>
					<?php echo CHtml::button('usuń grupę', array('submit'=>Yii::app()->createUrl('question/admin/removeQuestionGroup/id/'.$group->primaryKey.'/type/1'), 'confirm'=>'Czy na pewno usunąć tę grupę pytań?', 'class'=>'submenu button')); ?>
					<?php echo CHtml::button('dodaj pytanie', array('submit'=>Yii::app()->createUrl('question/admin/createQuestion/group_id/'.$group->primaryKey), 'class'=>'submenu button')) ?>
				</div>
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
