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
		return array(
			array('test_user_id, question_id', 'required'),
			array('test_user_id, question_id, last_change_date', 'numerical', 'integerOnly'=>true),
			array('score', 'numerical'),
			array('answer', 'safe'),
			
			array('id, test_user_id, question_id, answer, last_change_date, score', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'testUserAnswerLogs' => array(self::HAS_MANY, 'TestUserAnswerLog', 'test_log_id', 'order'=>'display_order ASC'),
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
			'score' => 'Score',
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
		$criteria->compare('test_user_id',$this->test_user_id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('last_change_date',$this->last_change_date);
		$criteria->compare('score',$this->score);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave() {
		$this->last_change_date = time();
		
		return parent::beforeSave();
	}
}