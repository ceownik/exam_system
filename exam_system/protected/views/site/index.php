
<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
	<h4 style="margin: 0px 0px 5px 0px;">nowe egzaminy</h4>
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'new-test-grid',
		'dataProvider'=>$model->searchForUser(),
		'filter'=>$model,
		'template'=>"{items}\n{pager}\n{summary}",
		'columns' => array(
			array(
				'name' => 'name',
				'htmlOptions'=>array('style'=>'min-width: 150px;'),
			),
			array(
				'name' => 'begin_time',
				'value' => 'date("Y-m-d H:i", $data->begin_time)',
				'htmlOptions'=>array('style'=>'width: 110px;'),
				'filter'=>false,
			),
			array(
				'name' => 'end_time',
				'value' => 'date("Y-m-d H:i", $data->end_time)',
				'htmlOptions'=>array('style'=>'width: 110px;'),
				'filter'=>false,
			),
			array(
				'name'=>'duration_time',
				'htmlOptions'=>array('style'=>'width: 110px;'),
				'filter'=>false,
			),
			array(
				'class' => 'application.extensions.kgridview.KButtonColumn',
				'template' => '{execute}{continue}',
				'header' => '',
				'buttons' => array(
					'execute' => array(
						'label' => 'Rozpocznij',
						'url' => 'Yii::app()->createUrl("/exam/execute")',
						'options' => array(
							'class' => 'submit-execute',
							'submit' => '/exam/execute',
							'params' => array(
								'execute-exam' => 'true',
								'exam_id' => '$data->id',
							),
						),
						'visible'=>'TestUserLog::checkStatus($data->id, Yii::app()->user->id) == TestUserLog::STATUS_NEW',
					),
					'continue' => array(
						'label' => 'Kontynuuj',
						'url' => 'Yii::app()->createUrl("/exam/execute")',
						'options' => array(
							'class' => 'submit-execute',
							'submit' => '/exam/execute',
							'params' => array(
								'continue-exam' => 'true',
								'exam_id' => '$data->id',
							),
						),
						'visible'=>'TestUserLog::checkStatus($data->id, Yii::app()->user->id) == TestUserLog::STATUS_STARTED',
					),
				),
			),
		),
	));
	?>
</div>
<?php 