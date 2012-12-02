<?php

/**
 * This is the model class for table "test".
 *
 * The followings are the available columns in table 'test':
 * @property integer $id
 * @property integer $create_date
 * @property integer $create_user
 * @property integer $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property integer $status
 * @property string $name
 * @property string $description
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $duration_time
 * @property integer $question_set_id
 * @property integer $question_set_version
 *
 * The followings are the available model relations:
 * @property QuestionSet $questionSet
 * @property QuestionGroup[] $questionGroups
 * @property TestUserLog[] $testUserLogs
 */
class Test extends CActiveRecord
{
	public $groupsIds;
	public $beginTime;
	public $endTime;
	
	const STATUS_NEW = 0;
	const STATUS_PREPARED = 1;
	const STATUS_CONFIRMED = 2;
	const STATUS_FINISHED = 3;
	
	protected static $_statusMap = array(
		self::STATUS_NEW => 'Nowy',
		self::STATUS_PREPARED => 'Przygotowany',
		self::STATUS_CONFIRMED => 'Zatwierdzony',
		self::STATUS_FINISHED => 'Zakończony',
	);
	
	public static function getStatusOptions() {
		return self::$_statusMap;
	}
	
	public static function getStatusDescription($status = null) {
		if($status === null)
			return null;
		
		if(isset(self::$_statusMap[$status])) {
			return self::$_statusMap[$status];
		} else {
			return null;
		}
	}
	
	public function getStatusText() {
		return self::getStatusDescription($this->status);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, beginTime, endTime, duration_time', 'required'),
			array('duration_time, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			array('description, groupsIds', 'safe'),
			array('endTime', 'compare', 'compareAttribute'=>'beginTime', 'operator'=>'>'),
			
			array('id, is_deleted, name, description, begin_time, end_time, duration_time, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'questionSet' => array(self::BELONGS_TO, 'QuestionSet', 'question_set_id'),
			'questionGroups' => array(self::MANY_MANY, 'QuestionGroup', 'test_question_group(test_id, group_id)'),
			'userGroups' => array(self::MANY_MANY, 'UserGroup', 'test_user_group(test_id, group_id)'),
			'testUserLogs' => array(self::HAS_MANY, 'TestUserLog', 'test_id'),
			'testQuestionGroups' => array(self::HAS_MANY, 'TestQuestionGroup', 'test_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'name' => 'Nazwa',
			'description' => 'Opis',
			'begin_time' => 'Początek dostępności',
			'end_time' => 'Koniec dostępności',
			'duration_time' => 'Czas trwania (min)',
			'question_set_id' => 'Zestaw pytań',
			'groupsIds' => 'Grupy użytkowników',
			'status' => 'Status',
			'beginTime' => 'Początek dostępności',
			'endTime' => 'Koniec dostępności',
			
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('begin_time',$this->begin_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('duration_time',$this->duration_time);
		$criteria->compare('question_set_id',$this->question_set_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchNew()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('begin_time',$this->begin_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('duration_time',$this->duration_time);
		$criteria->compare('status',$this->status);
		
		$criteria->addCondition('is_deleted = 0');
		$criteria->addCondition('begin_time > '.time());
		$criteria->addCondition('status IN (0,1)', 'OR');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'sort'=>array(
				'defaultOrder'=>'status desc, begin_time',
			),
		));
	}
	
	public function searchCurrent()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('begin_time',$this->begin_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('duration_time',$this->duration_time);
		$criteria->compare('status',$this->status);
		
		$criteria->addCondition('is_deleted = 0');
		//$criteria->addCondition('begin_time < '.time());
		$criteria->addCondition('status = 2');
		$criteria->having = '(end_time + duration_time) > '.time() .'';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'sort'=>array(
				'defaultOrder'=>'end_time',
			),
		));
	}
	
	public function searchCompleted()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('begin_time',$this->begin_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('duration_time',$this->duration_time);
		$criteria->compare('status',$this->status);
		
		$criteria->addCondition('is_deleted = 0');
		$criteria->addCondition('status = 2');
		$criteria->addCondition('(end_time + duration_time) < '.time() .'', 'AND');
		$criteria->addCondition('status = 3', 'OR');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'sort'=>array(
				'defaultOrder'=>'end_time DESC',
			),
		));
	}
	
	public function searchForUser() {
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.begin_time',$this->begin_time);
		$criteria->compare('t.end_time',$this->end_time);
		$criteria->compare('t.duration_time',$this->duration_time);
		$criteria->compare('t.status',$this->status);
		
		$criteria->addCondition('t.is_deleted = 0');
		$criteria->addCondition('t.begin_time < '.time());
		$criteria->addCondition('t.status = 2');
		$criteria->addCondition('coalesce((select log.status from test_user_log log where log.test_id=t.id AND log.user_id='.Yii::app()->user->id.'), 1)=1');
		$criteria->addCondition(Yii::app()->user->id.' IN (select assign.user_id from user_group_assignment assign where assign.group_id IN (select test.group_id from test_user_group test where test.test_id=t.id))');
		$criteria->having = '(t.end_time + t.duration_time) > '.time() .'';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'sort'=>array(
				'defaultOrder'=>'end_time',
			),
		));
	}
	
	public function afterFind() {
		if(!empty($this->userGroups)) {
			foreach($this->userGroups as $group) {
				$this->groupsIds[] = $group->id;
			}
		}
		
		$this->beginTime = date('Y-m-d H:i', $this->begin_time);
		$this->endTime = date('Y-m-d H:i', $this->end_time);
		
		parent::afterFind();
	}
	
	public function beforeValidate() {
		$this->begin_time = CDateTimeParser::parse($this->beginTime, 'yyyy-MM-dd HH:mm');
		$this->end_time = CDateTimeParser::parse($this->endTime, 'yyyy-MM-dd HH:mm');
		
		return parent::beforeValidate();
	}
	
	public function beforeSave() {
		$time = time();
		
		if($this->isNewRecord) {
			$this->create_user = Yii::app()->user->id;
			$this->create_date = $time;
		}
		
		$this->last_update_user = Yii::app()->user->id;
		$this->last_update_date = $time;
		
		return parent::beforeSave();
	}
	
	public function updateUserGroups($groupsIds) {
		$model = new TestUserGroup();
		$model->deleteAllByAttributes(array('test_id'=>$this->id));
		
		if(!empty($groupsIds)) {
			foreach($groupsIds as $id) {
				$model->unsetAttributes();
				$model->isNewRecord = true;
				$model->group_id = $id;
				$model->test_id = $this->id;

				$model->save();
			}
		}
	}
	
	public function updateQuestionGroups($id) {
		$model = new TestQuestionGroup();
		$model->deleteAllByAttributes(array('test_id'=>$this->id));
		
		$questionSet = QuestionSet::model()->findByPk($id);
		if($questionSet==null)
			KThrowException::throw404 ();
		
		if(!empty($questionSet->questionGroups)) {
			foreach($questionSet->questionGroups as $group) {
				$model->unsetAttributes();
				$model->isNewRecord = true;
				$model->test_id = $this->id;
				$model->group_id = $group->id;

				$model->save();
			}
		}
		return $questionSet->last_update_date;
	}
}