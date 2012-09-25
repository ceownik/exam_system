<div style="width: 100%; overflow: auto;">
<?php

$this->widget('application.extensions.kgridview.KGridView', array(
    'dataProvider'=>$items,
	'filter'=>$filter,
	'columns' => array(
		array(
			'name' => 'name',
			'value' => '$data->name',
		),
		array(
			'name' => 'description',
			'value' => '$data->description',
		),
		array(
			'class' => 'application.extensions.kgridview.KButtonColumn',
			'header' => 'Actions',
			'template' => '{view}{update}{delete}',
			'buttons' => array(
				'view' => array(
					'url' => 'Yii::app()->createUrl("/admin/rights/view/", array("name"=>$data->name))',
					'visible'=>"Yii::app()->user->checkAccess('rights.view_item_details')",
				),
				'update' => array(
					'url' => 'Yii::app()->createUrl("/admin/rights/update/", array("name"=>$data->name))',
					'visible'=>"Yii::app()->user->checkAccess('rights.update_item')",
				),
				'delete' => array(
					'label' => RightsModule::t(null, 'delete_label'),
					'url' => 'Yii::app()->createUrl("/admin/rights/delete/", array("name"=>$data->name))',
					'click'=>'function(){return confirm("'. RightsModule::t(null, 'delete_confirmation') . '")}',
					'visible'=>"Yii::app()->user->checkAccess('rights.delete_item')",
				),
			
			),
			'htmlOptions'=>array(
				'style'=>'text-align:center;',
			),
			'headerHtmlOptions'=>array(
				'width'=>'50px',
				'style'=>'text-align:center;',
			),
		),
	),
));

?></div>