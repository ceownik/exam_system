<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController' and 'AdminController'.
 */
class LoginForm extends CFormModel
{
	/**
	 * user's login
	 */
	public $username;
	
	
	/**
	 * user's password
	 */
	public $password;
	
	
	/**
	 * remember me field
	 */
	public $rememberMe;

	
	/**
	 * user's identyty (UserIdentity class object)
	 */
	private $_identity;

	
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			
			// username needs to match pattern
			array(
				'username', 'match', 'not' => true, 'pattern' => '/[^a-zA-Z0-9_-]/',
				'message' => Yii::t('app','Name must consist of letters, numbers, minus and underscore characters only.'),
			 ),
			
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>Yii::t('app', 'Pamiętaj następnym razem'),
			'username' => 'Login',
			'password' => 'Hasło',
		);
	}

	
	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password', Yii::t('app', 'Incorrect username or password.'));
		}
	}

	
	
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity = new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			// check if user can be remembered
			if( Yii::app()->user->allowAutoLogin )
				// if user has selected remember me oprion
				$duration = $this->rememberMe ? 3600*24*30 : 0; // 30 days
			else
				$duration = 0;
			
			if( Yii::app()->user->login($this->_identity, $duration) )
			{
				// user is logged in, update last login time
				User::model()->updateByPk( Yii::app()->user->id , array('last_login_date'=>time()) );
				
				return true;
			}
			return false;
		}
		else
			return false;
	}
}
