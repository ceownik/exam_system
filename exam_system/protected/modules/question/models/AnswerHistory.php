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
 * @property integer $item_order
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property Question $question
 */
class AnswerHistory extends CActiveRecord
{
	public $selected; // used in /admin/exam/scoreTest
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'answer_history';
	}
	
	public function rules()
	{
		return array(
			array('id, question_id, create_date, create_user, last_update_date, last_update_user, item_order', 'required'),
			array('id, question_id, create_date, create_user, last_update_date, last_update_user, is_deleted, is_correct, item_order', 'numerical', 'integerOnly'=>true),
			array('answer, description, enabled', 'safe'),
			
			array('history_id, id, question_id, create_date, create_user, last_update_date, last_update_user, is_deleted, answer, is_correct, description, item_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
		);
	}
	
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
			'item_order' => 'Item Order',
		);
	}
	
	public function search()
	{
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
		$criteria->compare('item_order',$this->item_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}