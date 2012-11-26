
<div style="width: 100%; overflow: auto;">
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px;">Szczegóły</h4>
		<div style="padding: 5px 0px 0px 15px;">
			<h5 style="margin: 0px 0px 0px 0px;">Nazwa</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $model->name; ?></div>

			<h5 style="margin: 0px 0px 0px 0px;">Opis</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $model->description; ?></div>
		</div>
	</div>
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">Członkowie grupy</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $userModel->searchGroupMembers($model->primaryKey),
			'filter'=>$userModel,
			'columns' => array(
				array(
					'name' => 'login',
				),
				array(
					'name' => 'display_name'
				),
				array(
					'name' => 'email',
				),
				array(
					'class' => 'application.extensions.kgridview.KButtonColumn',
					'template' => '{remove}',
					'header' => '',
					'buttons' => array(
						'remove' => array(
							'label' => 'Usuń',
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
		<h4 style="margin: 0px 0px 5px 0px;">Dodaj użytkowników</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $userModel->searchForGroup($model->primaryKey),
			'filter'=>$userModel,
			'columns' => array(
				array(
					'name' => 'login',
				),
				array(
					'name' => 'display_name'
				),
				array(
					'name' => 'email',
				),
				array(
					'class' => 'application.extensions.kgridview.KButtonColumn',
					'template' => '{add}',
					'header' => '',
					'buttons' => array(
						'add' => array(
							'label' => 'Dodaj',
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