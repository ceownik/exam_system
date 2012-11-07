<div>
	<div>type: <?php echo $question->getTypeText(); ?></div>
	<div style="float: left;">
		<div>
			<div></div>
			<div><?php echo $question->question; ?></div>
			
			
		</div>
	</div>
	<div style="float: right;">
		<div style="text-align: right;"><?php echo CHtml::button('edit', array('submit'=>Yii::app()->createUrl('question/admin/viewQuestion/id/'.$question->primaryKey), 'class'=>'submenu button')); ?></div>
		
	</div>
</div>