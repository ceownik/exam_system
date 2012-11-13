<?php

/**
 * This is the model class for table "question_group_history".
 *
 * The followings are the available columns in table 'question_group_history':
 * @property integer $history_id
 * @property integer $id
 * @property integer $set_id
 * @property integer $create_date
 * @property integer $create_user
 * @property integer $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property integer $enabled
 * @property string $name
 * @property string $description
 * @property integer $item_order
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property QuestionSet $set
 */
class QuestionGroupHistory extends KActiveRecord
{
	public $questions;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_group_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id, set_id, create_date, create_user, last_update_date, last_update_user, item_order', 'required'),
			array('id, set_id, create_date, create_user, last_update_date, last_update_user, is_deleted, item_order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('description, enabled', 'safe'),
			
			array('id, set_id, create_date, create_user, last_update_date, last_update_user, is_deleted, name, description, item_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'set' => array(self::BELONGS_TO, 'QuestionSet', 'set_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Question Group Id',
			'set_id' => 'Set',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'enabled' => 'Enabled',
			'name' => 'Name',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('set_id',$this->set_id);
		$criteria->compare('create_date',$this->create_date);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('last_update_date',$this->last_update_date);
		$criteria->compare('last_update_user',$this->last_update_user);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('item_order',$this->item_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeFind() {
		$this->dbCriteria->order = 'history_id DESC';
		parent::beforeFind();
	}
	
	public function afterFind() {
		
		parent::afterFind();
	}
	
	public function findQuestions() {
		$items = QuestionHistory::model()->findAllByAttributes(
			array(
				'group_id'=>$this->id,
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
				$model->answers = $model->findAnswers();
				$model->hasErrors = Question::validateQuestion($model);
			}
		}
		usort($models, function($a, $b){return $a->item_order > $b->item_order;});
		return $models;
	}
	
	public function getCorrectQuestionsCount($type=null) {
		$count = 0;
		foreach($this->questions as $q) {
			if(!$q->hasErrors && $q->enabled) {
				if($type==null || $type=="")
					++$count;
				elseif($type == Question::TYPE_MCSA)
					++$count;
			}
		}
		return $count;
	}
	
	public function getCorrectQuestions($type=null) {
		$questions = array();
		foreach($this->questions as $q) {
			if(!$q->hasErrors && $q->enabled) {
				if($type==null || $type == 0) {
					$questions[] = $q;
				} elseif($type == Question::TYPE_MCSA) {
					$questions[] = $q;
				}
			}
		}
		return $questions;
	}
}