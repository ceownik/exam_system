<?php

/**
 * This is the model class for table "test_user_log".
 *
 * The followings are the available columns in table 'test_user_log':
 * @property integer $id
 * @property integer $test_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $create_date
 * @property integer $last_change_date
 * @property integer $end_date
 *
 * The followings are the available model relations:
 * @property Test $test
 * @property User $user
 * @property TestUserQuestionLog[] $testUserQuestionLogs
 */
class TestUserLog extends CActiveRecord
{
	public $login_search;
	public $display_name_search;
	public $test_name_search;
	
	
	const STATUS_NEW = 0;
	const STATUS_STARTED = 1;
	const STATUS_COMPLETED = 2;
	const STATUS_CANCELED = 3;
	const STATUS_SCORED = 4;
	
	protected static $_statusMap = array(
		self::STATUS_NEW => 'Nowy',
		self::STATUS_STARTED => 'Rozpoczęty',
		self::STATUS_COMPLETED => 'Zakończony',
		self::STATUS_CANCELED => 'Anulowany',
		self::STATUS_SCORED => 'Oceniony',
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
		return 'test_user_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('test_id, user_id, status, create_date, last_change_date, end_date', 'required'),
			array('test_id, user_id, status, create_date, last_change_date, end_date', 'numerical', 'integerOnly'=>true),
			
			array('id, test_id, status, create_date, last_change_date, end_date, login_search, display_name_search, test_name_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'test' => array(self::BELONGS_TO, 'Test', 'test_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'testUserQuestionLogs' => array(self::HAS_MANY, 'TestUserQuestionLog', 'test_user_id', 'order'=>'id ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'test_id' => 'Test',
			'user_id' => 'User',
			'status' => 'Status',
			'create_date' => 'Data rozpoczęcia',
			'last_change_date' => 'Last Change Date',
			'end_date' => 'Data zakończenia',
			'display_name_search' => '',
			'score'=>'Ilość punktów',
			'mark'=>'Ocena',
			'passed'=>'Zaliczony',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('test_id',$this->test_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_date',$this->create_date);
		$criteria->compare('last_change_date',$this->last_change_date);
		$criteria->compare('end_date',$this->end_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchCompletedForUser() {
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'test'
		);

//		$criteria->compare('t.id',$this->id);
//		$criteria->compare('t.name',$this->name,true);
//		$criteria->compare('t.description',$this->description,true);
//		$criteria->compare('t.begin_time',$this->begin_time);
//		$criteria->compare('t.end_time',$this->end_time);
//		$criteria->compare('t.duration_time',$this->duration_time);
//		$criteria->compare('t.status',$this->status);
		$criteria->compare('test.name',$this->test_name_search, true);
//		
//		$criteria->addCondition('t.is_deleted = 0');
//		$criteria->addCondition('t.begin_time < '.time());
		$criteria->addCondition('t.status IN (2, 3, 4)');
		$criteria->addCondition('t.user_id='.Yii::app()->user->id.'');
//		$criteria->addCondition(Yii::app()->user->id.' IN (select assign.user_id from user_group_assignment assign where assign.group_id IN (select test.group_id from test_user_group test where test.test_id=t.id))');
//		$criteria->having = '(t.end_time + t.duration_time) > '.time() .'';
		
		
		
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
	
	public static function checkStatus($testId, $userId) {
		$model = TestUserLog::model()->findByAttributes(array(
			'test_id' => $testId,
			'user_id' => $userId,
		));
		if($model==null)
			return self::STATUS_NEW;
		
		return $model->status;
	}
	
	public function updateStatus($status = null) {
		if($status!==null) {
			$this->status = $status;
		} elseif($this->status==self::STATUS_STARTED) {
			if((int)$this->end_date <= time()) {
				$this->status = self::STATUS_COMPLETED;
			}
		}
		$this->last_change_date = time();
		return $this->update(array('last_change_date', 'status'));
	}
	
	public function cancelTestsByTestId($id) {
		
		Yii::app()->db->createCommand()->update('test_user_log', 
			array(
				'status'=>self::STATUS_CANCELED
			),
			'test_id=:test AND status=1',
			array(
				':test'=>$id,
			)
		);
	}
	
	public function getByTestId($id) {
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'user',
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.test_id',$this->test_id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.create_date',$this->create_date);
		$criteria->compare('t.last_change_date',$this->last_change_date);
		$criteria->compare('t.end_date',$this->end_date);
		$criteria->compare('user.login', $this->login_search, true);
		$criteria->compare('user.display_name', $this->display_name_search, true);
		
		$criteria->addCondition('t.test_id='.$id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function scoreSum() {
		$sum = 0;
		foreach($this->testUserQuestionLogs as $question) {
			if($question->score != null) {
				$sum += $question->score;
			}
		}
		
		return $sum;
	}
	
	public function getActiveTests() {
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'user',
			'test',
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.test_id',$this->test_id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.create_date',$this->create_date);
		$criteria->compare('t.last_change_date',$this->last_change_date);
		$criteria->compare('t.end_date',$this->end_date);
		$criteria->compare('user.login', $this->login_search, true);
		$criteria->compare('user.display_name', $this->display_name_search, true);
		$criteria->compare('test.name', $this->test_name_search, true);
		
		$criteria->addCondition('t.status = 1');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}