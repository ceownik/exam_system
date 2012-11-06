<div style="overflow: auto; <?php if($question->hasErrors){echo 'background-color: #f76f6f;';}?>">
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
						<div style="float: left">
							<?php echo CHtml::button('update answer', array('submit'=>Yii::app()->createUrl('question/admin/updateAnswer/id/'.$answer->primaryKey))); ?>
							<?php echo CHtml::button('remove answer', array('submit'=>Yii::app()->createUrl('question/admin/removeAnswer/id/'.$answer->primaryKey), 'confirm'=>'Czy na pewno usunąć to pytanie?')); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
		</div>
	</div>
	<div style="float: right;">
		<div>type: <?php echo $question->getTypeText(); ?></div>
		<div style="text-align: right;">
			<?php echo CHtml::button('update question', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestion/id/'.$question->primaryKey))); ?>
			<?php echo CHtml::button('remove question', array('submit'=>Yii::app()->createUrl('question/admin/removeQuestion/id/'.$question->primaryKey), 'confirm'=>'Czy na pewno usunąć tę odpowiedź?')); ?>
			<?php echo CHtml::button('add answer', array('submit'=>Yii::app()->createUrl('question/admin/addAnswer/id/'.$question->primaryKey))); ?>
		</div>
	</div>
</div>