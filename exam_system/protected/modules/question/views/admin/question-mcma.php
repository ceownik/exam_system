<div class="question type-mcma <?php if($question->hasErrors){echo 'errors';}?>" >
	<table class="question-table">
		<tr>
			<td class="question-number">
				<?php echo $questionCount; ?>.
			</td>
			<td class="question-question">
				<div class="question-body position-body"><?php echo $question->question; ?></div>
				<div class="position-description">
					<p>typ pytania: <?php echo $question->getTypeText(); ?></p>
					<?php if($question->hasErrors) : ?>
					<p><strong>pytanie zawiera błędy</strong></p>
					<?php endif; ?>
					<p>opis:</p>
					<div style="padding-left: 5px;"><?php echo $question->description; ?></div>
				</div>
			</td>
			<td class="question-menu">
				<div style="text-align: right;" class="content-submenu">
					<?php echo CHtml::button('menu pytania', array('submit'=>'#', 'class'=>'parent button')); ?>
					<div class="content-submenu-sub">
						<?php echo CHtml::button('edytuj', array('submit'=>Yii::app()->createUrl('question/admin/updateQuestion/id/'.$question->primaryKey), 'class'=>'submenu button')); ?>
						<?php echo $question->enabled ? 
						CHtml::button('zablokuj pytanie', array('submit'=>Yii::app()->createUrl('question/admin/disable/id/'.$question->primaryKey.'/type/question'), 'class'=>'submenu button')) :
						CHtml::button('odblokuj pytanie', array('submit'=>Yii::app()->createUrl('question/admin/enable/id/'.$question->primaryKey.'/type/question'), 'class'=>'submenu button'))?>
						<?php echo CHtml::button('usuń pytanie', array('submit'=>Yii::app()->createUrl('question/admin/removeQuestion/id/'.$question->primaryKey), 'confirm'=>'Czy na pewno usunąć to pytanie?', 'class'=>'submenu button')); ?>
						<?php echo CHtml::button('dodaj odpowiedź', array('submit'=>Yii::app()->createUrl('question/admin/addAnswer/id/'.$question->primaryKey), 'class'=>'submenu button')); ?>
					</div>
				</div>			
			</td>
		</tr>
		<?php foreach($question->answers as $answer) : ?>
		<tr>
			<td></td>
			<td class="answer-cell">
				<div class="dot <?php if($answer->enabled) echo 'green'; else echo 'grey'; ?>" title="odpowiedź aktywna"></div>
				<div class="dot <?php if($answer->is_correct) echo 'green'; else echo 'grey'; ?>" title="odpowiedź <?php if($answer->is_correct) echo 'poprawna'; else echo 'błędna'; ?>"></div>
				<div class="answer-body position-body"><?php echo $answer->answer; ?></div>
				<div class="position-description">
					<?php echo $answer->description; ?>
				</div>
			</td>
			<td class="answer-menu">
				<div style="" class="content-submenu">
					<?php echo CHtml::button('menu odpowiedzi', array('submit'=>'#', 'class'=>'parent button')); ?>
					<div class="content-submenu-sub">
						<?php echo CHtml::button('edytuj', array('submit'=>Yii::app()->createUrl('question/admin/updateAnswer/id/'.$answer->primaryKey), 'class'=>'submenu button')); ?>
						<?php echo $answer->enabled ? 
						CHtml::button('zablokuj odpowiedź', array('submit'=>Yii::app()->createUrl('question/admin/disable/id/'.$answer->primaryKey.'/type/answer'), 'class'=>'submenu button')) :
						CHtml::button('odblokuj odpowiedź', array('submit'=>Yii::app()->createUrl('question/admin/enable/id/'.$answer->primaryKey.'/type/answer'), 'class'=>'submenu button'))?>
						<?php echo CHtml::button('usuń odpowiedź', array('submit'=>Yii::app()->createUrl('question/admin/removeAnswer/id/'.$answer->primaryKey), 'confirm'=>'Czy na pewno usunąć tę odpowiedź?', 'class'=>'submenu button')); ?>
					</div>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<div class="separator"></div>