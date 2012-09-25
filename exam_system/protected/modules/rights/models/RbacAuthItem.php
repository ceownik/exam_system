<?php

/**
 * This is the model class for table "rbac_auth_item".
 *
 * The followings are the available columns in table 'rbac_auth_item':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 */
class RbacAuthItem extends KActiveRecord 
{
	/**
	 * instance of CAuthItem object or null
	 */
	public $authItem = null;
	
	
	
	/**
	 * if item is created for single user
	 */
	public $userId = null;
	
	
	
	/**
	 * 
	 */
	public $directlyAssigned = false;
	
	
	
	/**
	 * 
	 */
	public $typeName;
	
	
	
	/**
	 * 
	 */
	public $itemsTree;
	
	

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RbacAuthItem the static model class
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
		return Yii::app()->components['authManager']->itemTable;
	}

	
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array( 'name, type, description', 'required' ),
			
			array( 'name', 'unique' ),
			
			array( 'name', 'match', 'pattern' => '/^[\._\w]+$/' ),
			
			array( 'bizrule', 'safe' ),
			
			array( 'data', 'safe' ),
			
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				//		array('id, login, email, name, surname, birth_date, gender, add_street, add_building_number, add_city, add_post_code, add_post_office', 'safe', 'on'=>'search'),
		);
	}
	
	
	
	/**
	 * 
	 */
	public function init()
	{
		
	}
	
	
	
	/**
	 * 
	 */
	public function isProtected()
	{
		$table = Yii::app()->components['authManager']->protectedItemsTable;
		
		$db = Yii::app()->components['authManager']->db;
		
		$result = $db->createCommand()
				->select('itemname')
				->from($table)
				->where('itemname=:n', array(':n'=>$this->name))
				->queryRow();
		
		if( $result )
			return true;
		else
			return false;
	}
	
	
	
	/**
	 * returns correctly populated model or null if item is not found
	 */
	public function getAuthItem($itemName)
	{
		$item = Yii::app()->authManager->getAuthItem($itemName);
		
		if( $item != null )
		{
			$model = new RbacAuthItem;
			
			$model->name = $item->name;
			$model->description = $item->description;
			$model->type = $item->type;
			$model->bizrule = $item->bizrule;
			$model->data = $item->data;
			$model->authItem = $item;
			
			switch($item->type)
			{
				case 0:
					$model->typeName = 'Operation';
					break;
				case 1:
					$model->typeName = 'Task';
					break;
				case 2:
					$model->typeName = 'Role';
					break;
			}
			
			return $model;
		}
		
		return null;
	}
	
	
	
	/**
	 * returns array of correctly populated models or empty array
	 */
	public function getAuthItems($type = null, $user = null)
	{
		$authManager = Yii::app()->authManager;
		
		$items = $authManager->getAuthItems($type, $user);
		
		
		$models = array();
		
		foreach($items as $key => $item)
		{
			$models[$key] = new RbacAuthItem;
			
			$models[$key]->name = $item->name;
			$models[$key]->description = $item->description;
			$models[$key]->bizrule = $item->bizrule;
			$models[$key]->data = $item->data;
			$models[$key]->type = $item->type;
			
			if($user!==null)
			{
				$models[$key]->userId = $user;
				$models[$key]->directlyAssigned = true;
			}
			
			switch($item->type)
			{
				case 0:
					$models[$key]->typeName = 'Operation';
					break;
				case 1:
					$models[$key]->typeName = 'Task';
					break;
				case 2:
					$models[$key]->typeName = 'Role';
					break;
			}
			
			$models[$key]->authItem = $item;
		}
		
		
		return $models;
	}
	
	
	/**
	 * @param item string only
	 */
	public function getItemChildren($item, $user=null)
	{
		$authManager = Yii::app()->authManager;
		
		$children = $authManager->getItemChildren($item);
		
		//dump('in children');//dump($children, false, true, 2);
		$models = array();
		
		foreach($children as $key => $i)
		{
			$models[$key] = new RbacAuthItem;
			
			$models[$key]->name = $i->name;
			$models[$key]->description = $i->description;
			$models[$key]->bizrule = $i->bizrule;
			$models[$key]->data = $i->data;
			$models[$key]->type = $i->type;
			
			if($user!==null)
			{
				$models[$key]->userId = $user;
				$models[$key]->directlyAssigned = false;
			}
			
			switch($i->type)
			{
				case 0:
					$models[$key]->typeName = 'Operation';
					break;
				case 1:
					$models[$key]->typeName = 'Task';
					break;
				case 2:
					$models[$key]->typeName = 'Role';
					break;
			}
			
			$models[$key]->authItem = $i;
			
			//dump($key);dump($i->name);dump($models[$key]->name);
		}
		//foreach($models as $k => $m){dump($k);dump($m->name);}
		//dump($models, false, true, 5);
		return $models;
	}
	
	
	
	/**
	 * 
	 */
	public function getUserRightsRecursively($user, $inheritedOnly=true)
	{
		// get auth items directly assigned to user
		$items = $this->getAuthItems(null, $user);
		//dump($items, false, true, 3);
		
		$children = array();
		
		if(!$inheritedOnly)
			$children = $items;
		
		// for each directly assigned item get it's children
		foreach($items as $k => $item)
		{
			$c = $this->getChildrenRecursively($item->name, $user);
			
			$children = array_merge($children, $c);
		}
		
		return $children;
	}
	
	
	
	/**
	 * 
	 */
	public function getChildrenRecursively($itemName, $user=null)
	{	
		$item = $this->getAuthItem($itemName);
		//echo 'a';
		//dump($item->name);
		//dump($itemName);
		if($item!=null)
		{
			$children = array();
			// get children
			$children = $this->getItemChildren($itemName, $user);
			//dump($itemName);
			//dump($item->name);
			
			//dump($children, false, true, 3);
			$result = $children;
			
			foreach($children as $k => $child)
			{
				$recursive = $this->getChildrenRecursively($child->name, $user);
				
				$result = array_merge($result, $recursive);
			}
			//echo 'b';
			//dump($result, false, true, 3);
			return $result;
		}
		
		return array();
	}
	
	
	
	/**
	 * 
	 */
	public function getItemParents($item)
	{
		$parentsNames = Yii::app()->authManager->db->createCommand()
				->select( 'parent' )
				->from( Yii::app()->authManager->itemChildTable )
				->where( "child=:n", array(':n' => $item) )
				->queryAll();
		
		$parents = array();
		
		foreach($parentsNames as $p)
		{
			$parents[$p['parent']] = $this->getAuthItem($p['parent']);
			$parents[$p['parent']]->directlyAssigned = true;
		}
		
		return $parents;
	}
	
	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			
		);
	}

	
	
	/**
	 * 
	 */
	public function beforeValidate()
	{
		return parent::beforeValidate();
	}

	
	
	/**
	 * 
	 */
	protected function afterValidate()
	{
		parent::afterValidate();
		
		if( $this->bizrule=='' )
			$this->bizrule = null;
			
		if( $this->data=='' )
			$this->data = null;
	}

	
	
	/**
	 * 
	 */
	public function beforeSave()
	{
		if( !($this->hasErrors()) )
		{
			
		}

		return parent::beforeSave();
	}

}