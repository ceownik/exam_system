<?php Yii::app()->clientScript->registerScript('descriptions', 'questionSetGridShowDescription();'); ?>

<div style="width: 100%; overflow: auto;">
<?php
$this->widget('application.extensions.kgridview.KGridView', array(
	'id' => 'question-set-grid',
	'dataProvider'=>$model->search(),
	'template'=>"{items}\n{pager}",
	'columns' => array(
		array(
			'name' => 'name',
			'htmlOptions'=>array('style'=>'min-width: 150px;'),
		),
		array(
			'name' => 'description',
			'type' => 'raw',
			'value'=>'"<div>".$data->description."</div>"',
			'htmlOptions'=>array('class'=>'description'),
		),
		array(
			'name' => 'count',
			'type' => 'raw',
			'value' => 'count($data->userGroupAssignments)',
			'header' => 'Liczba użytkowników',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}',
			'viewButtonUrl'=>'Yii::app()->createUrl("admin/users/viewGroup/id/".$data->primaryKey);',
			'updateButtonUrl'=>'Yii::app()->createUrl("admin/users/updateGroup/id/".$data->primaryKey);',
			'deleteButtonUrl'=>'Yii::app()->createUrl("admin/users/deleteGroup/id/".$data->primaryKey);',
			'viewButtonLabel'=>'Wyświetl szczegóły',
			'updateButtonLabel'=>'Edytuj',
			'deleteButtonLabel'=>'Usuń',
			'deleteConfirmation'=>'Czy na pewno usunąć tę grupę?',
		),
	),
));
?></div>