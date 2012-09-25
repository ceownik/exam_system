<div style="width: 100%; overflow: auto;">
<?php

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$user,
    'attributes' => array(
		'login',
		'display_name',
		'email',
		array(
			'name' => 'is_active',
			'value' => $user->is_active ? 'true' : 'false',
		),
		array(
			'name' => 'status',
			'value' => Yii::app()->user->checkUserStatus_string($user->id),
		),
		array(
			'name' => 'active_from',
			'value' => ($user->active_from=='0') ? '' : date("Y-m-d H:i", $user->active_from),
		),
		array(
			'name' => 'active_to',
			'value' => ($user->active_to=='0') ? '' : date("Y-m-d H:i", $user->active_to),
		),
		array(
			'name' => 'last_login_date',
			'value' => ($user->last_login_date==null) ? 'never' : date("Y-m-d H:i", $user->last_login_date),
		),
		array(
			'name' => 'create_date',
			'value' => date("Y-m-d H:i", $user->create_date),
		),
		array(
			'name' => 'create_user',
			'value' => ($user->create_user != 0) ? $creator : "registered",
		),
	),
));


?></div>