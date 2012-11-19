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
	const STATUS_NEW = 0;
	const STATUS_STARTED = 1;
	const STATUS_COMPLETED = 2;
	const STATUS_CANCELED = 3;
	
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
			
			array('id, test_id, user_id, status, create_date, last_change_date, end_date, user_comment', 'safe', 'on'=>'search'),
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
}