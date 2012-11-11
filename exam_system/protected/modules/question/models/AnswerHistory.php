<?php

/**
 * This is the model class for table "answer_history".
 *
 * The followings are the available columns in table 'answer_history':
 * @property integer $history_id
 * @property integer $id
 * @property integer $question_id
 * @property integer $create_date
 * @property integer $create_user
 * @property integer $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property integer $enabled
 * @property string $answer
 * @property integer $is_correct
 * @property string $description
 * @property integer $correct_order
 * @property string $column_left
 * @property string $column_right
 * @property integer $item_order
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property Question $question
 */
class AnswerHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnswerHistory the static model class
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
		return 'answer_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, question_id, create_date, create_user, last_update_date, last_update_user, item_order', 'required'),
			array('id, question_id, create_date, create_user, last_update_date, last_update_user, is_deleted, is_correct, correct_order, item_order', 'numerical', 'integerOnly'=>true),
			array('answer, description, column_left, column_right, enabled', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('history_id, id, question_id, create_date, create_user, last_update_date, last_update_user, is_deleted, answer, is_correct, description, correct_order, column_left, column_right, item_order', 'safe', 'on'=>'search'),
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
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'history_id' => 'History',
			'id' => 'ID',
			'question_id' => 'Question',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'enabled' => 'Enabled',
			'answer' => 'Answer',
			'is_correct' => 'Is Correct',
			'description' => 'Description',
			'correct_order' => 'Correct Order',
			'column_left' => 'Column Left',
			'column_right' => 'Column Right',
			'item_order' => 'Item Order',
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

		$criteria->compare('history_id',$this->history_id);
		$criteria->compare('id',$this->id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('create_date',$this->create_date);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('last_update_date',$this->last_update_date);
		$criteria->compare('last_update_user',$this->last_update_user);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('is_correct',$this->is_correct);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('correct_order',$this->correct_order);
		$criteria->compare('column_left',$this->column_left,true);
		$criteria->compare('column_right',$this->column_right,true);
		$criteria->compare('item_order',$this->item_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}