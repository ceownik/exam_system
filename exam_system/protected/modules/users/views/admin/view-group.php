
<div style="width: 100%; overflow: auto;">
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px;">details</h4>
		<div style="padding: 5px 0px 0px 15px;">
			<h5 style="margin: 0px 0px 0px 0px;">name</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $model->name; ?></div>

			<h5 style="margin: 0px 0px 0px 0px;">description</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $model->description; ?></div>
		</div>
	</div>
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">members</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $userModel->searchGroupMembers($model->primaryKey),
			'filter'=>$userModel,
			'columns' => array(
				array(
					'name' => 'login',
				),
				array(
					'name' => 'email',
				),
				
				array(
					'class' => 'application.extensions.kgridview.KButtonColumn',
					'template' => '{remove}',
					'header' => 'Actions',
					'buttons' => array(
						'remove' => array(
							'label' => 'Remove',
							'url' => null,
							'options' => array(
								'class' => 'submit-remove',
								'submit' => '',
								'params' => array(
									'remove-user' => 'true',
									'user' => '$data->id',
								),
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
		?>
	</div>

	

	<div style="border: 1px solid #ccc; padding: 5px;">
		<h4 style="margin: 0px 0px 5px 0px;">add user</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $userModel->searchForGroup($model->primaryKey),
			'filter'=>$userModel,
			'columns' => array(
				array(
					'name' => 'login',
				),
				array(
					'name' => 'email',
				),
				
				array(
					'class' => 'application.extensions.kgridview.KButtonColumn',
					'template' => '{add}',
					'header' => 'Actions',
					'buttons' => array(
						'add' => array(
							'label' => 'Add',
							'url' => null,
							'options' => array(
								'class' => 'submit-add',
								'submit' => '',
								'params' => array(
									'add-user' => 'true',
									'user' => '$data->id',
								),
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
		?>
	</div>

</div>