<?php Yii::app()->clientScript->registerScript('descriptions', 'gridShowDescription();'); ?>

<div style="width: 100%; overflow: auto;">
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">nowe testy</h4>
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
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_time',
					'value' => 'date("Y-m-d H:i", $data->end_time)',
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name'=>'duration_time',
					'headerHtmlOptions'=>array('style'=>'width: 75px;'),
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
		<h4 style="margin: 0px 0px 5px 0px;">testy zatwierdzone do wykonania</h4>
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
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_time',
					'value' => 'date("Y-m-d H:i", $data->end_time)',
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name'=>'duration_time',
					'headerHtmlOptions'=>array('style'=>'width: 75px;'),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{end} {statistics}',
					'buttons'=>array(
						'end'=>array(
							'label'=>'(zakończ)',
							'url'=>'Yii::app()->createUrl("/admin/exam/endTest/id/".$data->primaryKey)',
							'click'=>'function() {return confirm("zakończyć test?");}',
						),
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
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">testy w trakcie wykonywania</h4>
		<?php
		$this->widget('application.extensions.kgridview.KGridView', array(
			'id' => 'active-test-grid',
			'dataProvider'=>$testUserLog->getActiveTests(),
			'filter'=>$testUserLog,
			'template'=>"{items}\n{pager}",
			'columns' => array(
				array(
					'name' => 'test_name_search',
					'value' => '$data->test->name',
					'header' => 'Nazwa testu',
					'htmlOptions'=>array('style'=>'min-width: 150px;'),
				),
				array(
					'name' => 'login_search',
					'type' => 'raw',
					'header' => 'Login',
					'value'=>'$data->user->login',
				),
				array(
					'name' => 'display_name_search',
					'type' => 'raw',
					'header' => 'Nazwa użytkownika',
					'value'=>'$data->user->display_name',
				),
				array(
					'name' => 'create_date',
					'value' => 'date("Y-m-d H:i", $data->create_date)',
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_date',
					'value' => '$data->status == 1 ? "" : date("Y-m-d H:i", $data->end_date)',
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
//				array(
//					'name'=>'duration_time',
//					'headerHtmlOptions'=>array('style'=>'width: 70px;'),
//				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{end}',
					'buttons'=>array(
						'end'=>array(
							'label'=>'(zakończ)',
							'url'=>'Yii::app()->createUrl("/admin/exam/endUserTest/id/".$data->primaryKey)',
							'click'=>'function() {return confirm("zakończyć test?");}',
						),
					),
				),
			),
		));
		?>
	</div>
	
	<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
		<h4 style="margin: 0px 0px 5px 0px;">testy zakończone</h4>
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
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name' => 'end_time',
					'value' => 'date("Y-m-d H:i", $data->end_time)',
					'headerHtmlOptions'=>array('style'=>'width: 90px;'),
				),
				array(
					'name'=>'duration_time',
					'headerHtmlOptions'=>array('style'=>'width: 75px;'),
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