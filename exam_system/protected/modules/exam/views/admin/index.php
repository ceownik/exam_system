<?php Yii::app()->clientScript->registerScript('descriptions', 'gridShowDescription();'); ?>

<div style="width: 100%; overflow: auto;">
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">nowe egzaminy</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'id' => 'new-test-grid',
			'dataProvider'=>$model->searchNew(),
			'filter'=>$model,
			'template'=>"{items}\n{pager}",
			'columns' => array(
				array(
					'name' => 'name',
					'htmlOptions'=>array('style'=>'min-width: 150px;'),
				),
				array(
					'name' => 'description',
					'type' => 'raw',
					'value'=>'"<div>".$data->description."</div>"',
					'htmlOptions'=>array('class'=>'description', 'style'=>'max-width: 300px;'),
				),
				array(
					'name' => 'begin_time',
					'value' => 'date("Y-m-d H:i", $data->begin_time)',
					'htmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_time',
					'value' => 'date("Y-m-d H:i", $data->end_time)',
					'htmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name'=>'duration_time',
					'htmlOptions'=>array('style'=>'width: 60px;'),
				),
				array(
					'name'=>'status',
					'value'=>'$data->getStatusText();',
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{edit} {configure} {confirm}',
					'buttons'=>array(
						'edit'=>array(
							'label'=>'(edytuj)',
							'url'=>'Yii::app()->createUrl("/admin/exam/update/id/".$data->primaryKey)',
						),
						'configure'=>array(
							'label'=>'(konfiguruj)',
							'visible'=>'($data->status==0 || $data->status==1)',
							'url'=>'Yii::app()->createUrl("/admin/exam/configure/id/".$data->primaryKey)',
						),
						'confirm'=>array(
							'label'=>'(zatwierdź)',
							'visible'=>'($data->status==1)',
							'url'=>'Yii::app()->createUrl("/admin/exam/confirmExam/id/".$data->primaryKey)',
							'click'=>'function() {return confirm("zatwierdzić egzamin do przeprowadzenia?");}',
						),
					),
				),
			),
		));
		?>
	</div>
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">egzaminy w trakcie wykonywania</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'id' => 'current-test-grid',
			'dataProvider'=>$model->searchCurrent(),
			'filter'=>$model,
			'template'=>"{items}\n{pager}",
			'columns' => array(
				array(
					'name' => 'name',
					'htmlOptions'=>array('style'=>'min-width: 150px;'),
				),
				array(
					'name' => 'description',
					'type' => 'raw',
					'value'=>'"<div>".$data->description."</div>"',
					'htmlOptions'=>array('class'=>'description', 'style'=>'max-width: 300px;'),
				),
				array(
					'name' => 'begin_time',
					'value' => 'date("Y-m-d H:i", $data->begin_time)',
					'htmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_time',
					'value' => 'date("Y-m-d H:i", $data->end_time)',
					'htmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name'=>'duration_time',
					'htmlOptions'=>array('style'=>'width: 60px;'),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{end}',
					'buttons'=>array(
						'end'=>array(
							'label'=>'(zakończ)',
							'url'=>'Yii::app()->createUrl("/admin/exam/endTest/id/".$data->primaryKey)',
							'click'=>'function() {return confirm("zakończyć test?");}',
						),
					),
				),
			),
		));
		?>
	</div>
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">egzaminy zakończone</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'id' => 'completed-test-grid',
			'dataProvider'=>$model->searchCompleted(),
			'filter'=>$model,
			'template'=>"{items}\n{pager}",
			'columns' => array(
				array(
					'name' => 'name',
					'htmlOptions'=>array('style'=>'min-width: 150px;'),
				),
				array(
					'name' => 'description',
					'type' => 'raw',
					'value'=>'"<div>".$data->description."</div>"',
					'htmlOptions'=>array('class'=>'description', 'style'=>'max-width: 300px;'),
				),
				array(
					'name' => 'begin_time',
					'value' => 'date("Y-m-d H:i", $data->begin_time)',
					'htmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_time',
					'value' => 'date("Y-m-d H:i", $data->end_time)',
					'htmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name'=>'duration_time',
					'htmlOptions'=>array('style'=>'width: 60px;'),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{statistics}',
					'buttons'=>array(
						'statistics'=>array(
							'label'=>'(podsumowanie)',
							'url'=>'Yii::app()->createUrl("/admin/exam/testSummary/id/".$data->primaryKey)',
						),
					),
				),
			),
		));
		?>
	</div>
</div>