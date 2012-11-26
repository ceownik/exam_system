<div style="width: 100%; overflow: auto;">


	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px;">Szczegóły</h4>
		<div style="padding: 5px 0px 0px 15px;">
			<h5 style="margin: 0px 0px 0px 0px;">Nazwa</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $item->name; ?></div>

			<h5 style="margin: 0px 0px 0px 0px;">Typ</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $item->typeName; ?></div>

			<h5 style="margin: 0px 0px 0px 0px;">Opis</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $item->description; ?></div>

			<h5 style="margin: 0px 0px 0px 0px;">BizRule</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $item->bizrule; ?></div>

			<h5 style="margin: 0px 0px 0px 0px;">Dane</h5>
			<div style="padding: 0px 0px 10px 15px;"><?php echo $item->data; ?></div>
		</div>
	</div>
	
	
		
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">Pozycje nadrzędne (rodzice)</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $parents,
			'filter' => $filterParents,
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
					'class' => 'CDataColumn',
					'header' => 'Relacja',
					'value' => '($data->directlyAssigned) ? "Bezpośrednio" : "Powiązane";',
					'headerHtmlOptions' => array(
						'style' => 'width: 70px; text-align: center;',
					),
					'htmlOptions' => array(
						'style' => 'text-align: center;',
					),
				),
			),
			'summaryText' => '',
		));
		?>
	</div>

	
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">Pozycje podrzędne (dzieci)</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $children,
			'filter' => $filterChildren,
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
					'template' => '{remove}{text}',
					'header' => '',
					'buttons' => array(
						'remove' => array(
							'label' => 'Usuń',
							'url' => null,
							'options' => array(
								'class' => 'submit-remove',
								'submit' => '',
								'params' => array(
									'remove-child' => 'true',
									'child' => '$data->name',
								),
							),
							'visible' => '$data->directlyAssigned',
						),
						'text' => array(
							'label' => 'Inherited',
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
		?>
	</div>

	

	<div style="border: 1px solid #ccc; padding: 5px;">
		<h4 style="margin: 0px 0px 5px 0px;">Dodaj pozycję podrzędną</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'dataProvider' => $potentialChildren,
			'filter' => $filterPotentialChildren,
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
					'class' => 'KButtonColumn',
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
									'add-child' => 'true',
									'child' => '$data->name',
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

	
<style type="text/css">
	
</style>
	
</div>