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
 * @property integer $item_order
 * @property integer $last_change_date
 *
 * The followings are the available model relations:
 * @property TestUserQuestionLog $testLog
 * @property Answer $answer
 */
class TestUserAnswerLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TestUserAnswerLog the static model class
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
		return 'test_user_answer_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('test_log_id, answer_id, display_order', 'required'),
			array('test_log_id, answer_id, display_order, selected, item_order, last_change_date', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, test_log_id, answer_id, display_order, selected, item_order, last_change_date', 'safe', 'on'=>'search'),
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
			'item_order' => 'Item Order',
			'last_change_date' => 'Last Change Date',
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
		$criteria->compare('test_log_id',$this->test_log_id);
		$criteria->compare('answer_id',$this->answer_id);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('selected',$this->selected);
		$criteria->compare('item_order',$this->item_order);
		$criteria->compare('last_change_date',$this->last_change_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}