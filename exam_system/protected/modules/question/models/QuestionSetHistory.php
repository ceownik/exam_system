<?php

/**
 * This is the model class for table "question_set_history".
 *
 * The followings are the available columns in table 'question_set_history':
 * @property integer $history_id
 * @property integer $id
 * @property integer $create_date
 * @property integer $create_user
 * @property string $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property integer $enabled
 * @property string $name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property QuestionSet $id0
 */
class QuestionSetHistory extends CActiveRecord
{
	public $questionGroups = array();
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_set_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id, create_date, create_user, last_update_date, last_update_user', 'required'),
			array('id, create_date, create_user, last_update_user, is_deleted', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('description, enabled', 'safe'),
			
			array('id, create_date, create_user, last_update_date, last_update_user, is_deleted, name, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'questionSet' => array(self::BELONGS_TO, 'QuestionSet', 'id'),
			//'questionGroups' => array(self::HAS_MANY, 'QuestionGroupHistory', 'set_id', 'order'=>'questionGroups.history_id DESC', 'group'=>'questionGroups.id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Question Set Id',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'enabled' => 'Enabled',
			'name' => 'Name',
			'description' => 'Description',
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
		$criteria->compare('create_date',$this->create_date);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('last_update_date',$this->last_update_date,true);
		$criteria->compare('last_update_user',$this->last_update_user);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function findQuestionGroups() {
		$items = QuestionGroupHistory::model()->findAllByAttributes(
			array(
				'set_id'=>$this->id,
			), 
			array(
				'condition'=>'last_update_date <= '.$this->last_update_date,
				'order'=>'history_id DESC, item_order ASC',
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
				$models[$id]->questions = $models[$id]->findQuestions();
			}
		}
		usort($models, function($a, $b){return $a->item_order > $b->item_order;});
		return $models;
	}
	
	public function afterFind() {
		$this->questionGroups = $this->findQuestionGroups();
		
		parent::afterFind();
	}
}