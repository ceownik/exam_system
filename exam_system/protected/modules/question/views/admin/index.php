<div style="width: 100%; overflow: auto;">
<?php
$this->widget('application.extensions.kgridview.KGridView', array(
	'dataProvider'=>$model->search(),
	'columns' => array(
		array(
			'name' => 'name',
		),
		array(
			'name' => 'description',
			'type' => 'raw',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
			'viewButtonUrl'=>'Yii::app()->createUrl("admin/question/viewQuestionSet/id/".$data->primaryKey);',
			'updateButtonUrl'=>'Yii::app()->createUrl("admin/question/updateQuestionSet/id/".$data->primaryKey);',
		),
	),
));
?></div>