<div style="overflow: auto;">
	<h3 style="margin: 0px 0px 10px 0px;">Uprawnienia przyznane użytkownikowi</h3>
<?php

$this->widget('application.extensions.kgridview.KGridView', array(
    'dataProvider' => $assignedRights,
	'filter'=>$filterAssignedRights,
	'template'=>"{items}\n{pager}",
	'columns' => array(
		array(
			'name' => 'name',
			'header' => 'Nazwa',
			'value' => '$data->name',
		),
		array(
			'name' => 'description',
			'header' => 'Opis',
			'value' => '$data->description',
		),
		array(
			'name' => 'type',
			'header' => 'Typ',
			'value' => '$data->typeName',
			'headerHtmlOptions' => array(
				'style' => 'width: 70px; text-align: center;',
			),
			'htmlOptions' => array(
				'style' => 'text-align: center;',
			),
			'filter' => array('0'=>'Operacja', '1'=>'Zadanie', '2'=>'Rola'),
		),
		array(
			'class' => 'application.extensions.kgridview.KButtonColumn',
			'template' => '{revoke}{text}',
			'header' => 'Actions',
			'buttons' => array(
				'revoke' => array(
					'label' => 'Odwołaj',
					'url' => 'Yii::app()->createUrl("admin/rights/assignment", array("id"=>"'.$user->id.'"))',
					'options' => array(
						'class' => 'submit-revoke',
						'submit' => '',
						'params' => array(
							'revoke' => 'true',
							'revoke-item' => '$data->name',
						),
					),
					'visible' => '$data->directlyAssigned && Yii::app()->user->checkAccess("rights.manage_user_assignments")',
				),
				'text' => array(
					'label' => 'Odziedziczone',
					'visible' => '!$data->directlyAssigned',
					'url' => null,
					'options' => array(
						'style' => 'text-decoration:none; color: black;',
					),
				),
			),
			'headerHtmlOptions' => array(
				'style' => 'width: 70px; text-align: center;',
			),
		),
	),
	'summaryText' => '',
));

?></div>


<div style="overflow: auto; margin: 30px 0px 0px 0px;">
	<h3 style="margin: 0px 0px 10px 0px; clear:both;">Nadaj uprawnienie</h3>
		
<?php

$this->widget('application.extensions.kgridview.KGridView', array(
    'dataProvider' => $rightsToAssign,
	'filter' => $filterRightsToAssign,
	'template'=>"{items}\n{pager}",
	'columns' => array(
		array(
			'name' => 'name',
			'header' => 'Nazwa',
			'value' => '$data->name',
		),
		array(
			'name' => 'description',
			'header' => 'Opis',
			'value' => '$data->description',
		),
		array(
			'name' => 'type',
			'header' => 'Typ',
			'value' => '$data->typeName',
			'headerHtmlOptions' => array(
				'style' => 'width: 70px; text-align: center;',
			),
			'htmlOptions' => array(
				'style' => 'text-align: center;',
			),
			'filter' => array('0'=>'Operacja', '1'=>'Zadanie', '2'=>'Rola'),
		),
		array(
			'class' => 'application.extensions.kgridview.KButtonColumn',
			'template' => '{assign}',
			'header' => 'Actions',
			'buttons' => array(
				'assign' => array(
					'label' => 'Przypisz',
					'options' => array(
						'class' => 'submit-assign',
						'submit' => '',
						'params' => array(
							'assign' => 'true',
							'assign-item' => '$data->name',
						),
					),
					'visible' =>  'Yii::app()->user->checkAccess("rights.manage_user_assignments")',
				),
			),
			'headerHtmlOptions' => array(
				'style' => 'width: 70px; text-align: center;',
			),
		),
	),
	'summaryText' => '',
));

?></div>