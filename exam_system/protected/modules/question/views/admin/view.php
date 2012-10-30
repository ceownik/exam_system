<div style="width: 100%; overflow: auto;">
	<div>
		name:
		<?php echo $model->name; ?>
	</div>
	<div>
		description:
		<?php echo $model->description; ?>
	</div>
	
	<hr />	
	groups:
	
	<?php foreach($model->questionGroups as $group) : ?>
		<div><?php echo $group->name; ?></div>
	<?php endforeach; ?>
</div>

<?php

$this->widget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'confirmation-response-dialog',
    'options'=>array(
        'title'=>false,
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>600,	
    ),
));

?>