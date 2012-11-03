<?php

/**
 * This is the model class for table "question_group".
 *
 * The followings are the available columns in table 'question_group':
 * @property integer $id
 * @property integer $set_id
 * @property integer $create_date
 * @property integer $create_user
 * @property integer $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property string $name
 * @property string $description
 * @property integer $item_order
 *
 * The followings are the available model relations:
 * @property Question[] $questions
 * @property User $createUser
 * @property QuestionSet $set
 * @property QuestionHistory[] $questionHistories
 */
class QuestionGroup extends KActiveRecord
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
		return 'question_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('set_id, create_date, create_user, last_update_date, last_update_user, is_deleted, item_order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('description', 'safe'),
			
			array('id, set_id, create_date, create_user, last_update_date, last_update_user, is_deleted, name, description, item_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'questions' => array(self::HAS_MANY, 'Question', 'group_id'),
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'set' => array(self::BELONGS_TO, 'QuestionSet', 'set_id'),
			'questionHistories' => array(self::HAS_MANY, 'QuestionHistory', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'set_id' => 'Set',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'name' => 'Name',
			'description' => 'Description',
			'item_order' => 'Item Order',
		);
	}

	/**
	 * 
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
	
	public function beforeSave() {
		$time = time();
		
		if($this->isNewRecord) {
			$this->create_user = Yii::app()->user->id;
			$this->create_date = $time;
			$this->item_order = $this->getOrder($this->set_id);
		}
		
		$this->last_update_user = Yii::app()->user->id;
		$this->last_update_date = $time;
		
		return parent::beforeSave();
	}
	
	public function afterSave() {
		$history = new QuestionGroupHistory;
		$history->attributes = $this->attributes;
		$history->save();
		
		$questionSet = QuestionSet::model()->findByPk($this->set_id);
		$questionSet->afterUpdate();
		
		parent::afterSave();
	}
	
	public function createDefault($set_id) {
		$model = new QuestionGroup;
		$model->name = "Default";
		$model->set_id = $set_id;
		$model->save();
	}
	
	private function getOrder($set_id) {
		var_dump($set_id);
		$model = $this->findByAttributes(array('set_id'=>$set_id), array(), 'order by item_order desc');
		if(!$model) {
			return 1;
		} else {
			return $model->item_order + 1;
		}
	}
}