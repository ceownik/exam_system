<?php

/**
 * This is the model class for table "question_set".
 *
 * The followings are the available columns in table 'question_set':
 * @property integer $id
 * @property integer $create_date
 * @property integer $create_user
 * @property string $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property string $name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property QuestionGroup[] $questionGroups
 * @property QuestionGroupHistory[] $questionGroupHistories
 * @property User $createUser
 * @property QuestionSetHistory[] $questionSetHistories
 */
class QuestionSet extends KActiveRecord
{
	private $createDefaultQuestionGroup = false;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_set';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>128),
			array('description', 'safe'),
			
			array('id, create_date, create_user, last_update_date, last_update_user, is_deleted, name, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'questionGroups' => array(self::HAS_MANY, 'QuestionGroup', 'set_id', 'condition'=>'questionGroups.is_deleted=0', 'order'=>'questionGroups.item_order ASC'),
			'questionGroupHistory' => array(self::HAS_MANY, 'QuestionGroupHistory', 'set_id'),
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'questionSetHistory' => array(self::HAS_MANY, 'QuestionSetHistory', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		
		$criteria->addCondition('is_deleted IS NOT NULL');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
	}
	
	public function beforeSave() {
		$time = time();
		
		if($this->isNewRecord) {
			$this->create_user = Yii::app()->user->id;
			$this->create_date = $time;
			$this->createDefaultQuestionGroup = true;
		}
		
		$this->last_update_user = Yii::app()->user->id;
		$this->last_update_date = $time;
		
		return parent::beforeSave();
	}
	
	public function afterSave() {
		$history = new QuestionSetHistory;
		$history->attributes = $this->attributes;
		$history->save();
		
		if($this->createDefaultQuestionGroup) {
			QuestionGroup::model()->createDefault($this->primaryKey);
		}
		
		return parent::afterSave();
	}
}