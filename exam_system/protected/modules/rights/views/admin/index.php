<div style="width: 100%; overflow: auto;">
<?php
if(Yii::app()->user->checkAccess('rights.view_assingments'))
{
		$this->widget('application.extensions.kgridview.KGridView', array(
		'dataProvider'=>$usersData,
		'filter'=>$filter,
		'template'=>"{items}\n{pager}",
		'columns' => array(
			array(
				'name' => 'login',
				'header'=>'Login',
				'value' => 'CHtml::link($data["login"], array("/admin/rights/assignment/id/".$data["id"]))',
				'type' => 'raw',
			),
			array(
				'name' => 'roles',
				'header' => 'Role',
				'value' => '$data["roles"]',
				'type' => 'html',
				'headerHtmlOptions'=>array(
					'width'=>'25%',
				),
			),
			array(
				'name' => 'tasks',
				'header' => 'Zadania',
				'value' => '$data["tasks"]',
				'type' => 'html',
				'headerHtmlOptions'=>array(
					'width'=>'25%',
				),
			),
			array(
				'name' => 'operations',
				'header' => 'Operacje',
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