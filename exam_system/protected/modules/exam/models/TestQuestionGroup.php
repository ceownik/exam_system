<?php

/**
 * This is the model class for table "test_question_group".
 *
 * The followings are the available columns in table 'test_question_group':
 * @property integer $test_id
 * @property integer $group_id
 */
class TestQuestionGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TestQuestionGroup the static model class
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
		return 'test_question_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('test_id, group_id', 'required'),
			array('test_id, group_id', 'numerical', 'integerOnly'=>true),
			array('question_types, question_quantity, answers', 'safe'),
			
			array('test_id, group_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'questionGroup' => array(self::BELONGS_TO, 'QuestionGroup', 'group_id'),
			'test' => array(self::BELONGS_TO, 'Test', 'test_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'test_id' => 'Test',
			'group_id' => 'Group',
			'question_types' => 'Question Type',
			'question_quantity' => 'Question Quantity',
			'answers' => 'Ilość odpowiedzi w pytaniach zamkniętych',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('test_id',$this->test_id);
		$criteria->compare('group_id',$this->group_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}