<?php

/**
 * This is the model class for table "test_user_answer_log".
 *
 * The followings are the available columns in table 'test_user_answer_log':
 * @property integer $id
 * @property integer $test_log_id
 * @property integer $answer_id
 * @property integer $display_order
 * @property integer $selected
 * @property integer $last_change_date
 *
 * The followings are the available model relations:
 * @property TestUserQuestionLog $testLog
 * @property Answer $answer
 */
class TestUserAnswerLog extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'test_user_answer_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('test_log_id, answer_id, display_order', 'required'),
			array('test_log_id, answer_id, display_order, selected, last_change_date', 'numerical', 'integerOnly'=>true),
			
			array('id, test_log_id, answer_id, display_order, selected, last_change_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'testLog' => array(self::BELONGS_TO, 'TestUserQuestionLog', 'test_log_id'),
			'answer' => array(self::BELONGS_TO, 'Answer', 'answer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'test_log_id' => 'Test Log',
			'answer_id' => 'Answer',
			'display_order' => 'Display Order',
			'selected' => 'Selected',
			'last_change_date' => 'Last Change Date',
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
		$criteria->compare('test_log_id',$this->test_log_id);
		$criteria->compare('answer_id',$this->answer_id);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('selected',$this->selected);
		$criteria->compare('last_change_date',$this->last_change_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave() {
		$this->last_change_date = time();
		return parent::beforeSave();
	}
}