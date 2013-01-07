<?php

/**
 * This is the model class for table "question".
 *
 * The followings are the available columns in table 'question':
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
 * @property Answer[] $answers
 * @property AnswerHistory[] $answerHistories
 * @property User $createUser
 * @property QuestionGroup $group
 */
class Question extends KActiveRecord
{
	const TYPE_MCSA = 1;
	const TYPE_MCMA = 2;
	
	public $hasErrors = false;
	public $hasCorrectAnswer = false;
	
	protected static $_typesMap = array(
		self::TYPE_MCSA => 'Jednokrotnego wyboru',
		self::TYPE_MCMA => 'Wielokrotnego wyboru',
	);

	public static function getTypesOptions() {
		return self::$_typesMap;
	}
	
	public static function getTypeDescription($type=null) {
		if($type==null)
			return null;
		
		if(isset(self::$_typesMap[$type])) {
			return self::$_typesMap[$type];
		} else {
			return null;
		}
	}
	
	public function getTypeText() {
		return self::getTypeDescription($this->type);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('type, question', 'required'),
			array('question, description, enabled', 'safe'),
			
			array('id, group_id, create_date, create_user, last_update_date, last_update_user, is_deleted, type, question, description, item_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'answers' => array(self::HAS_MANY, 'Answer', 'question_id', 'condition'=>'answers.is_deleted=0'),
			'answerHistories' => array(self::HAS_MANY, 'AnswerHistory', 'question_id'),
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
			'group' => array(self::BELONGS_TO, 'QuestionGroup', 'group_id'),
			'questionHistories' => array(self::HAS_MANY, 'QuestionHistory', 'id', 'order'=>'id DESC'),
			'testUserQuestionLogs' => array(self::HAS_MANY, 'TestUserQuestionLog', 'question_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'group_id' => 'Group',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
			'is_deleted' => 'Is Deleted',
			'enabled' => 'Enabled',
			'type' => 'Typ',
			'question' => 'Pytanie',
			'description' => 'Opis',
			'item_order' => 'Item Order',
		);
	}

	/**
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

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
	
	public function beforeSave() {
		$time = time();
		
		if($this->isNewRecord) {
			$this->create_user = Yii::app()->user->id;
			$this->create_date = $time;
			$this->item_order = $this->getOrder($this->group_id);
		}
		
		$this->last_update_user = Yii::app()->user->id;
		$this->last_update_date = $time;
		
		return parent::beforeSave();
	}
	
	public function afterSave() {
		$history = new QuestionHistory;
		$history->attributes = $this->attributes;
		$history->isNewRecord = true;
		$history->save();
		
		$questionGroup = QuestionGroup::model()->findByPk($this->group_id);
		$questionGroup->afterUpdate();
		
		return parent::afterSave();
	}
	
	public function afterUpdate() {
		if($this->isNewRecord) {
			return false;
		}
		
		return $this->save(true, array('last_update_user', 'last_update_date'));
	}
	
	private function getOrder($set_id) {
		$model = $this->findByAttributes(array('group_id'=>$set_id), array('order'=>'item_order desc'));
		if(!$model) {
			return 1;
		} else {
			return $model->item_order + 1;
		}
	}
	
	public function afterFind() {
		$this->hasErrors = self::validateQuestion($this);
		parent::afterFind();
	}
	
	public static function validateQuestion($question) {
		if($question->type == self::TYPE_MCSA) {
			$correctCount = 0;
			$wrongCount = 0;
			$enabledCount = 0;
			foreach($question->answers as $answer) {
				if($answer->is_correct && ($answer->enabled)) {
					$correctCount++;
				} elseif(!$answer->is_correct && $answer->enabled) {
					$wrongCount++;
				}
				if($answer->enabled) {
					$enabledCount++;
				}
			}
			if($correctCount >= 1) {
				$question->hasCorrectAnswer = true;
			} else {
				$question->hasErrors = true;
			}
			
			if($wrongCount<1) {
				$question->hasErrors = true;
			}
			
			if($enabledCount < 2) {
				$question->hasErrors = true;
			}
		} elseif($question->type == self::TYPE_MCMA) {
			$correctCount = 0;
			$wrongCount = 0;
			$enabledCount = 0;
			foreach($question->answers as $answer) {
				if($answer->is_correct && ($answer->enabled)) {
					$correctCount++;
				} elseif(!$answer->is_correct && $answer->enabled) {
					$wrongCount++;
				}
				if($answer->enabled) {
					$enabledCount++;
				}
			}
			if($correctCount >= 1) {
				$question->hasCorrectAnswer = true;
			} else {
				$question->hasErrors = true;
			}
			
			if($enabledCount < 2) {
				$question->hasErrors = true;
			}
		}
		return $question->hasErrors;
	}
}