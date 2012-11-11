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
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TestUserLog the static model class
	 */
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('test_id, user_id, status, create_date, last_change_date, end_date', 'required'),
			array('test_id, user_id, status, create_date, last_change_date, end_date', 'numerical', 'integerOnly'=>true),
			array('user_comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, test_id, user_id, status, create_date, last_change_date, end_date, user_comment', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'test' => array(self::BELONGS_TO, 'Test', 'test_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'testUserQuestionLogs' => array(self::HAS_MANY, 'TestUserQuestionLog', 'test_user_id'),
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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

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
}