<div class="question type-mcsa <?php if($question->hasErrors){echo 'errors';}?>" style="overflow: auto; ">
	<div style="float: left;">
		<div>
			<span style="float: left;"><?php echo $questionCount; ?>.</span>
			<div style="float: left;">
				<div><?php echo $question->question; ?></div>
				<?php foreach($question->answers as $answer) : ?>
					<div style="display:block; overflow: auto;">
						<div style="float: left; min-width: 300px;">
							<?php echo $answer->answer; ?>
						</div>
						<div style="float: left" class="content-submenu">
							<?php echo CHtml::button('update answer', array('submit'=>Yii::app()->createUrl('question/admin/updateAnswer/id/'.$answer->primaryKey), 'class'=>'submenu button')); ?>
							<?php echo CHtml::button('remove answer', array('submit'=>Yii::app()->createUrl('question/admin/removeAnswer/id/'.$answer->primaryKey), 'confirm'=>'Czy na pewno usunąć to pytanie?', 'class'=>'submenu button')); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
		</div>
	</div>
	<div style="float: right;">
		<div>type: <?php echo $question->getTypeText(); ?></div>
		<div style="text-align: right;" class="content-submenu">
			<?php echo CHtml::button('update question', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestion/id/'.$question->primaryKey), 'class'=>'submenu button')); ?>
			<?php echo CHtml::button('remove question', array('submit'=>Yii::app()->createUrl('question/admin/removeQuestion/id/'.$question->primaryKey), 'confirm'=>'Czy na pewno usunąć tę odpowiedź?', 'class'=>'submenu button')); ?>
			<?php echo CHtml::button('add answer', array('submit'=>Yii::app()->createUrl('question/admin/addAnswer/id/'.$question->primaryKey), 'class'=>'submenu button')); ?>
		</div>
	</div>
</div>