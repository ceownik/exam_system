<div style="width: 100%; overflow: auto;">
<?php
if(Yii::app()->user->checkAccess('rights.view_assingments'))
{
		$this->widget('application.extensions.kgridview.KGridView', array(
		'dataProvider'=>$usersData,
		'filter'=>$filter,
		'columns' => array(
			array(
				'name' => 'login',
				'header'=>'login',
				'value' => 'CHtml::link($data["login"], array("/admin/rights/assignment/id/".$data["id"]))',
				'type' => 'raw',
			),
			array(
				'name' => 'roles',
				'value' => '$data["roles"]',
				'type' => 'html',
				'headerHtmlOptions'=>array(
					'width'=>'25%',
				),
			),
			array(
				'name' => 'tasks',
				'value' => '$data["tasks"]',
				'type' => 'html',
				'headerHtmlOptions'=>array(
					'width'=>'25%',
				),
			),
			array(
				'name' => 'operations',
				'value' => '$data["operations"]',
				'type' => 'html',
				'headerHtmlOptions'=>array(
					'width'=>'25%',
				),
			),
		),

	));
}
else
{
	
}



?></div><?php 