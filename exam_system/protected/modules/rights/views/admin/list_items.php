<div style="width: 100%; overflow: auto;">
<?php

$this->widget('application.extensions.kgridview.KGridView', array(
    'dataProvider'=>$items,
	'filter'=>$filter,
		'template'=>"{items}\n{pager}",
	'columns' => array(
		array(
			'name' => 'name',
			'value' => '$data->name',
			'header' => 'Nazwa'
		),
		array(
			'name' => 'description',
			'value' => '$data->description',
			'header' => 'Opis'
		),
		array(
			'class' => 'application.extensions.kgridview.KButtonColumn',
			'header' => '',
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
					'url' => 'Yii::app()->createUrl("/admin/rights/delete/", array("name"=>$data->name))',
					'click'=>'function(){return confirm("Czy na pewno usunąć pozycję?")}',
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