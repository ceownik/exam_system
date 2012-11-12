<?php

/**
 * This is the model class for table "test_user_question_log".
 *
 * The followings are the available columns in table 'test_user_question_log':
 * @property integer $id
 * @property integer $test_user_id
 * @property integer $question_id
 * @property string $answer
 * @property integer $last_change_date
 * @property string $user_comment
 * @property double $score
 *
 * The followings are the available model relations:
 * @property TestUserAnswerLog[] $testUserAnswerLogs
 * @property TestUserLog $testUser
 * @property Question $question
 */
class TestUserQuestionLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TestUserQuestionLog the static model class
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
		return 'test_user_question_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('test_user_id, question_id', 'required'),
			array('test_user_id, question_id, last_change_date', 'numerical', 'integerOnly'=>true),
			array('score', 'numerical'),
			array('answer, user_comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, test_user_id, question_id, answer, last_change_date, user_comment, score', 'safe', 'on'=>'search'),
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
			'testUserAnswerLogs' => array(self::HAS_MANY, 'TestUserAnswerLog', 'test_log_id'),
			'testUser' => array(self::BELONGS_TO, 'TestUserLog', 'test_user_id'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'test_user_id' => 'Test User',
			'question_id' => 'Question',
			'answer' => 'Answer',
			'last_change_date' => 'Last Change Date',
			'user_comment' => 'User Comment',
			'score' => 'Score',
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
		$criteria->compare('test_user_id',$this->test_user_id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('last_change_date',$this->last_change_date);
		$criteria->compare('user_comment',$this->user_comment,true);
		$criteria->compare('score',$this->score);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}