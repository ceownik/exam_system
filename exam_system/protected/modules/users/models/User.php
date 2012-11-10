<?php
// TODO: klasa skopiowana ze starego projektu, nalezy ją całkowicie zmienić.

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $login
 * @property string $display_name
 * @property string $email
 * @property string $password
 * 
 * @property boolean $is_active
 * @property int $active_from - linux timestamp
 * @property int $active_to - linux timestamp
 * @property boolean $is_deleted
 * 
 * @property int $create_date
 * @property integer $create_user
 * @property int $last_update_date
 * @property integer $last_update_user
 * 
 * @property int $last_login_date
 */
class User extends KActiveRecord {

	// list of attributes to save while creating or updating object
	public $attributes_to_save = null;
	
	
	// creating new or updating user requires repeating password
	public $password_repeat;
	public $old_password;
	public $new_password;
	
	
	// if user should be active from time of creation or from anothre date
	public $active_from_now;
	
	// active_from, active_to - date, time variables
	public $active_from_date;
	public $active_to_date;
	
	
	
	/**
	 * user's status (calculated after find)
	 * (active/inactive)
	 */
	public $status;
	

	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model( $className = __CLASS__ )
	{
		return parent::model( $className );
	}

	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$this->init();
		return $this->tablePrefix . 'user';
	}

	
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array( 'login, password, password_repeat, email', 'required', 'on' => 'create' ),
			
			array( 'login, display_name, email', 'length', 'max' => 255, 'on' => 'create' ),
			
			array( 'login', 'length', 'min' => 4, 'on' => 'create' ),
			
			array( 'password', 'length', 'max' => 64, 'on' => 'create' ),
			
			array( 'password', 'length', 'min' => 4, 'on' => 'create' ),
			
			array( 'password_repeat', 'length', 'max' => 64, 'on' => 'create, passwd' ),
			
			array( 'password_repeat', 'length', 'min' => 4, 'on' => 'create, passwd' ),
			
			array( 'is_active, active_from_now', 'match', 'pattern' => '/^[01]$/', 'on' => 'create, update' ),
			
			// login and email - unique
			array( 'login, email', 'unique', 'on' => 'create' ),
			
			array( 'email', 'email', 'on' => 'create' ),
			
			// username must match regexp
			array( 'login', 'match', 'pattern' => '/^[A-Za-z0-9_-]+$/', 'on' => 'create' ),
			
			array( 'display_name', 'match', 'pattern' => '/^[ A-Za-z0-9_-]+$/', 'on' => 'create, update' ),
			
			array( 'password_repeat', 'safe' ),
			
			// compare $password to $password_repeat
			array( 'password_repeat', 'compare', 'allowEmpty' => false, 'compareAttribute' => 'password', 'on' => 'create' ),
			
			array( 'active_from_date', 'date', 'format' => 'yyyy-MM-dd HH:mm', 'allowEmpty' => true, 'on' => 'create, update' ),
			array( 'active_to_date', 'date', 'format' => 'yyyy-MM-dd HH:mm', 'on' => 'create, update' ),
			
			
			
			array( 'display_name', 'length', 'max' => 255, 'on' => 'update' ),
			
			
			array( 'old_password, new_password', 'length', 'max' => 64, 'on' => 'passwd' ),
			array( 'old_password, new_password', 'length', 'min' => 4, 'on' => 'passwd' ),
			array( 'password_repeat', 'compare', 'allowEmpty' => false, 'compareAttribute' => 'new_password', 'on' => 'passwd' ),
			array( 'old_password', 'authenticate', 'on' => 'passwd' ),
			
//			array( 'active_from_time, active_to_time', 'match', 'pattern' => '/^(([01][0-9])|([2][0-3]))[:](([0-5][0-9])|([0-5][0-9][:][0-5][0-9]))$/', 'on' => 'create' ),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
			array('login, email ', 'safe', 'on'=>'search'),
		);
	}

	
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'answers' => array(self::HAS_MANY, 'Answer', 'create_user'),
			'answerHistories' => array(self::HAS_MANY, 'AnswerHistory', 'create_user'),
			'questions' => array(self::HAS_MANY, 'Question', 'create_user'),
			'questionGroups' => array(self::HAS_MANY, 'QuestionGroup', 'create_user'),
			'questionGroupHistories' => array(self::HAS_MANY, 'QuestionGroupHistory', 'create_user'),
			'questionHistories' => array(self::HAS_MANY, 'QuestionHistory', 'create_user'),
			'questionSets' => array(self::HAS_MANY, 'QuestionSet', 'create_user'),
			'questionSetHistories' => array(self::HAS_MANY, 'QuestionSetHistory', 'create_user'),
			'userGroupAssignments' => array(self::HAS_MANY, 'UserGroupAssignment', 'user_id'),
		);
	}

	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'User ID',
			'login' => 'Login',
			'display_name' => 'User display name',
			'password' => 'Password',
			'email' => 'Email',
			'is_active' => 'Is Active',
			'active_from' => 'Active From',
			'active_from_now' => 'Active From',
			'active_to' => 'Active To',
			'is_deleted' => 'Is Deleted',
			'create_date' => 'Create Date',
			'create_user' => 'Create User',
			'last_update_date' => 'Last Update Date',
			'last_update_user' => 'Last Update User',
		);
	}

	
	
	/**
	 * Before validation function
	 */
	public function beforeValidate()
	{
		
		return parent::beforeValidate();
	}

	protected function afterValidate()
	{
		
		parent::afterValidate();
	}

	
	
	public static function encrypt( $value )
	{
		$counter = 1500;
		
		$firstSalt = '';
		
		$secondSalt = '';
		
		$tmp = $value;
		
		while( $counter != 0 )
		{
			$tmp = md5($tmp);
			
			if( $counter == 1000 )
				$firstSalt = $tmp;
			
			if( $counter == 500 )
				$secondSalt = $tmp;
			
			
			--$counter;
		}
		
		return $tmp . '' . md5( $firstSalt . $secondSalt );
	}

	
	
	/**
	 * perform one-way encryption on the password before we store it in the database
	 */
	public function beforeSave()
	{
		if( !($this->hasErrors()) )
		{
			// common settings
			($this->is_active == '1') ? ($this->is_active = true) : ($this->is_active = false);


			// settings for new record (insert)
			if( $this->isNewRecord )
			{
				$this->password = self::encrypt( $this->password );

				// if user is created by another user
				if( $this->scenario == 'create' )
					$this->create_user = Yii::app()->user->id;
				// or if this is a register process (or else?)
				else
					$this->create_user = 0;

				if( !Yii::app()->user->isGuest )
					$this->last_update_user = Yii::app()->user->id;
				else
					$this->last_update_user = 0;
				
				$this->create_date = time();
				
			}

			// settings for existing record (update)
			else
			{
				$this->last_update_user = Yii::app()->user->id;
				
				if( $this->scenario == 'passwd' )
				{
					$this->password = self::encrypt( $this->new_password );
				}
				
			}
			
			// format dates to linux timestamp
			if( $this->active_from_now )
				$this->active_from = time();
			else
				$this->active_from = CDateTimeParser::parse ($this->active_from_date, 'yyyy-MM-dd HH:mm');
			
			$this->active_to = CDateTimeParser::parse ($this->active_to_date, 'yyyy-MM-dd HH:mm');
		}

		return parent::beforeSave();
	}
	
	
	
	/**
	 * 
	 */
	public function afterSave()
	{
		
	}

	
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider( $this, array(
			'criteria' => $criteria,
		) );
	}
	
	
	public function searchGroupMembers($id) {
		$criteria = new CDbCriteria;
		
		$criteria->select = 't.*';
		
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.login',$this->login,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.is_active',$this->is_active);
		
		$criteria->addCondition('t.is_deleted = 0');
		$criteria->addCondition('user_group_assignment.group_id = '.$id);
		
		$criteria->join = 'RIGHT JOIN user_group_assignment ON t.id = user_group_assignment.user_id';
		
		return new CActiveDataProvider( $this, array(
			'criteria' => $criteria,
		) );
	}
	
	
	public function searchForGroup($id) {
		$criteria = new CDbCriteria;
		
		$criteria->select = 't.*';
		
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.login',$this->login,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.is_active',$this->is_active);
		
		$criteria->addCondition('t.is_deleted = 0');
		$criteria->addCondition('t.id NOT IN (select assign.user_id from user_group_assignment assign where group_id = '.$id.')');
		
		return new CActiveDataProvider( $this, array(
			'criteria' => $criteria,
		) );
	}
	
	
	
	/**
	 * 
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if( self::encrypt($this->old_password) != $this->password )
				$this->addError('password', Yii::t('app', 'Incorrect password.'));
		}
	}
	
	
	
	/**
	 * 
	 */
	public function afterFind()
	{
		parent::afterFind();
		
		// calculate user's status
		$this->status = Yii::app()->user->checkUserStatus_string($this->id);
	}
}