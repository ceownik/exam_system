<div style="width: 100%; overflow: auto;">
<?php

if(Yii::app()->user->checkAccess('users.view_users_list'))
{

	$this->widget('application.extensions.kgridview.KGridView', array(
		'dataProvider'=>$data,
		'filter' => $filter,
		'columns' => array(
			array(
				'class' => 'CCheckBoxColumn',
				'selectableRows' => 2,
			),
			'id'=>array(
				'name' => 'id',
				'header' => 'User Id',
			),
			'login'=>array(
				'name' => 'login',
				'header' => 'Login',
			),
			'display_name'=>array(
				'name' => 'display_name',
				'header' => 'Display name',
			),
			'email'=>array(
				'name' => 'email',
				'header' => 'Email',
			),

			array(
				'name' => 'status',
				'header' => 'Status',
				'value' => '$data->status',
			),
//			array(
//				'name' => 'active_from',
//				'value' => '($data->active_from == "0") ? "" : date("Y-m-d H:i", $data->active_from)',
//			),
//			array(
//				'name' => 'active_to',
//				'value' => '($data->active_to == "0") ? "" : date("Y-m-d H:i", $data->active_to)',
//			),
			array(
				'class' => 'CButtonColumn',
				'header' => 'Actions',
				'template' => '{view}{update}',
				'buttons' => array(
					'view' => array(
						'url' => 'Yii::app()->createUrl("/admin/users/view/", array("id"=>$data->id))',
					),
					'update' => array(
						'url' => 'Yii::app()->createUrl("/admin/users/update/", array("id"=>$data->id))',
					),
				),
			),
		),
	));

}

?></div>