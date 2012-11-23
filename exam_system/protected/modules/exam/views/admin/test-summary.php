<?php Yii::app()->clientScript->registerScript('descriptions', 'gridShowDescription();'); ?>

<div style="width: 100%; overflow: auto;">
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;"><?php echo $model->name; ?></h4>
		<div>
			<?php echo $model->description; ?> 
		</div>
	</div>	
	<?php
	$this->widget('application.extensions.kgridview.KGridView', array(
		'id' => 'new-test-grid',
		'dataProvider'=>$testUserLog->getByTestId($model->id),
		'filter'=>$testUserLog,
		'template'=>"{items}\n{pager}\n{summary}",
		'columns' => array(
			array(
				'name' => 'login_search',
				'value' => '$data->user->login',
				'header' => 'User'
			),
			array(
				'name' => 'create_date',
				'value' => 'date("Y-m-d H:i", $data->create_date)',
				'htmlOptions'=>array('style'=>'width: 90px;'),
				'header' => 'Data rozpoczęcia'
			),
			array(
				'name'=>'status',
				'value'=>'$data->getStatusText();',
			),
			array(
				'name'=>'score',
				'value'=>'($data->status==4) ? $data->scoreSum() : ""',
				'filter'=>false,
				'sortable'=>false,
			),
			array(
				'name'=>'user_comment',
				'header'=>'Komentarz',
				'filter'=>false,
				'sortable'=>false,
			),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{details} {score}',
				'buttons'=>array(
					'details'=>array(
						'label'=>'(szczegóły)',
						'url'=>'Yii::app()->createUrl("/admin/exam/configure/id/".$data->primaryKey)',
						'visible'=>'false',
					),
					'score'=>array(
						'label'=>'(oceń)',
						'url'=>'Yii::app()->createUrl("/admin/exam/scoreTest/id/".$data->primaryKey)',
					),
				),
			),
		),
	));
	?>

</div>