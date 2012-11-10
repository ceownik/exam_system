<?php

/**
 * This is the model class for table "user_group".
 *
 * The followings are the available columns in table 'user_group':
 * @property integer $id
 * @property integer $create_date
 * @property integer $create_user
 * @property integer $last_update_date
 * @property integer $last_update_user
 * @property integer $is_deleted
 * @property string $name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property UserGroupHistory[] $userGroupHistories
 */
class UserGroup extends CActiveRecord
{
	/**
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
		return 'user_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>512),
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
			'userGroupHistories' => array(self::HAS_MANY, 'UserGroupHistory', 'id'),
			'userGroupAssignments' => array(self::HAS_MANY, 'UserGroupAssignment', 'group_id'),
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
		$criteria->compare('last_update_date',$this->last_update_date);
		$criteria->compare('last_update_user',$this->last_update_user);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		
		$criteria->addCondition('is_deleted = 0');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave() {
		$time = time();
		
		if($this->isNewRecord) {
			$this->create_user = Yii::app()->user->id;
			$this->create_date = $time;
		}
		
		$this->last_update_user = Yii::app()->user->id;
		$this->last_update_date = $time;
		
		return parent::beforeSave();
	}
	
	public function afterSave() {
		$history = new UserGroupHistory;
		$history->attributes = $this->attributes;
		$history->save();
		
		return parent::afterSave();
	}
	
	public function afterUpdate() {
		if($this->isNewRecord) {
			return false;
		}
		
		return $this->save(true, array('last_update_user', 'last_update_date'));
	}
	
	public static function checkAssignment($groupId, $userId) {
		$model = UserGroupAssignment::model()->find('group_id = :group AND user_id = :user', array(
			':group' => $groupId,
			':user' => $userId,
		));
		
		return $model;
	}
	
	public function hasUser($userId) {
		$model = self::checkAssignment($this->id, $userId);
		if($model!=null)
			return $model;
		else 
			return false;
	}
	
	public static function createAssignment($groupId, $userId) {
		$model = new UserGroupAssignment;
		$model->group_id = $groupId;
		$model->user_id = $userId;
		try {
			if($model->save())
				return true;
			else
				return false;
		} catch (CDbException $e) {
			return false;
		}
	}
	
	public function addUser($userId) {
		return self::createAssignment($this->id, $userId);
	}
}