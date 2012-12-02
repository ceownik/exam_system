
<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;" class="user-informations">
	<img src="/images/user_icon.png" alt="user" class="user-icon" />
	<div class="user-info">
		<h2 class="user-name"><?php echo (strlen($user->display_name)!=0) ? $user->display_name : $user->login; ?></h2>
		<table class="user-info-table">
			<tr>
				<th>
					email
				</th>
				<td>
					<?php echo $user->email; ?>
				</td>
			</tr>
			<tr>
				<th>
					konto aktywne do
				</th>
				<td>
					<?php echo ($user->active_to!=0) ? date('Y-m-d H:i', $user->active_to) : 'zawsze aktywne'; ?>
				</td>
			</tr>
		</table>
	</div>
</div>

<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
	<h4 style="margin: 0px 0px 5px 0px;">dostępne testy</h4>
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'new-test-grid',
		'dataProvider'=>$model->searchForUser(),
		'filter'=>$model,
		'template'=>"{items}\n{pager}",
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



<div style="border: 1px solid #ccc; padding: 5px; margin: 0px 0px 10px 0px;">
	<h4 style="margin: 0px 0px 5px 0px;">rozwiązane testy</h4>
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'completed-test-grid',
		'dataProvider'=>$testLog->searchCompletedForUser(),
		'filter'=>$testLog,
		'template'=>"{items}\n{pager}",
		'columns' => array(
			array(
				'name' => 'test_name_search',
				'htmlOptions'=>array('style'=>'min-width: 150px;'),
				'value'=>'$data->test->name',
				'header'=>'Nazwa',
			),
			array(
				'name' => 'create_date',
				'value' => 'date("Y-m-d H:i", $data->create_date)',
				'htmlOptions'=>array('style'=>'width: 110px;'),
				'filter'=>false,
			),
			array(
				'name' => 'end_date',
				'value' => 'date("Y-m-d H:i", $data->last_change_date)',
				'htmlOptions'=>array('style'=>'width: 110px;'),
				'filter'=>false,
			),
			array(
				'name'=>'status',
				'value'=>'$data->getStatusText();',
				'filter'=>false,
			),
			array(
				'name'=>'score',
				'value'=>'($data->status==4) ? $data->scoreSum() : ""',
				'filter'=>false,
				'sortable'=>false,
			),
			array(
				'name'=> 'mark',
				'filter'=>false,
				'sortable'=>false,
			),
			array(
				'name'=> 'passed',
				'value'=>'$data->passed == 1 ? "Tak" : "Nie"',
				'filter'=>false,
				'sortable'=>false,
			),
//			array(
//				'name'=>'duration_time',
//				'htmlOptions'=>array('style'=>'width: 110px;'),
//				'filter'=>false,
//			),
			array(
				'class' => 'application.extensions.kgridview.KButtonColumn',
				'template' => '',
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