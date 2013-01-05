<?php

/**
 * This is the model class for table "question_history".
 *
 * The followings are the available columns in table 'question_history':
 * @property integer $history_id
 * @property integer $id
 * @property integer $group_id
 * @property integer $create_date
 * @property integer $create_user
 * @property integer $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property integer $enabled
 * @property integer $type
 * @property string $question
 * @property string $description
 * @property integer $item_order
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property QuestionGroup $group
 */
class QuestionHistory extends CActiveRecord
{
	public $hasCorrectAnswer;
	public $hasErrors;
	public $answers;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('group_id, create_date, create_user, last_update_date, last_update_user, type, item_order', 'required'),
			array('id, group_id, create_date, create_user, last_update_date, last_update_user, is_deleted, type, item_order', 'numerical', 'integerOnly'=>true),
			array('question, description, enabled', 'safe'),
			
			array('history_id, id, group_id, create_date, create_user, last_update_date, last_update_user, is_deleted, type, question, description, item_order', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'QuestionGroup', 'group_id'),
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
			'group_id' => 'Group',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'enabled' => 'Enabled',
			'type' => 'Type',
			'question' => 'Question',
			'description' => 'Description',
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
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('create_date',$this->create_date);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('last_update_date',$this->last_update_date);
		$criteria->compare('last_update_user',$this->last_update_user);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('type',$this->type);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('item_order',$this->item_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTypeText() {
		return Question::getTypeDescription($this->type);
	}
	
	public function afterFind() {
		
		parent::afterFind();
	}
	
	public function findAnswers() {
		$items = AnswerHistory::model()->findAllByAttributes(
			array(
				'question_id'=>$this->id,
			), 
			array(
				'condition'=>'last_update_date <= '.$this->last_update_date,
				'order'=>'history_id DESC',
			)
		);
		$models = array();
		foreach($items as $item) {
			if(!isset($models[$item->id])) {
				$models[$item->id] = $item;
			}
		}
		foreach($models as $id=>$model) {
			if($model->is_deleted) {
				unset($models[$id]);
			} else {
				
			}
		}
		
		usort($models, function($a, $b){return $a->item_order > $b->item_order;});
		return $models;
	}
}