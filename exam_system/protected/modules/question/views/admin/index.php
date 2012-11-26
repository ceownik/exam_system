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
		),
		array(
			'name' => 'description',
			'type' => 'raw',
			'value'=>'"<div>".$data->description."</div>"',
			'htmlOptions'=>array('class'=>'description'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("admin/question/viewQuestionSet/id/".$data->primaryKey);',
			'updateButtonUrl'=>'Yii::app()->createUrl("admin/question/updateQuestionSet/id/".$data->primaryKey);',
		),
	),
));
?></div>