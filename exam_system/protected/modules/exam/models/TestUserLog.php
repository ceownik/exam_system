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
 * @property string $user_comment
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
			array('user_comment', 'safe'),
			
			array('id, test_id, status, create_date, last_change_date, end_date, user_comment, login_search, display_name_search, test_name_search', 'safe', 'on'=>'search'),
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
			'create_date' => 'Create Date',
			'last_change_date' => 'Last Change Date',
			'end_date' => 'End Date',
			'user_comment' => 'User Comment',
			'display_name_search' => '',
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
		$criteria->compare('user_comment',$this->user_comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
		} else {
			if($this->end_date < time()) {
				$this->status = self::STATUS_COMPLETED;
			}
		}
		$this->last_change_date = time();
		return $this->save();
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
		$criteria->compare('t.user_comment',$this->user_comment,true);
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
		$criteria->compare('t.user_comment',$this->user_comment,true);
		$criteria->compare('user.login', $this->login_search, true);
		$criteria->compare('user.display_name', $this->display_name_search, true);
		$criteria->compare('test.name', $this->test_name_search, true);
		
		$criteria->addCondition('t.status = 1');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}