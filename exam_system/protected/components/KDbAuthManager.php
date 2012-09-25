<?php

/**
 * DbAuthManager is an RBAC manager
 * 
 */
class KDbAuthManager extends CDbAuthManager 
{
	/**
	 * 
	 */
	public $inDebugMode = true;
	
	
	
	/**
	 * 
	 */
	private $isInstalled = false;
	
	
	/**
	 * includes names of items that can't be deleted and whose name can't be changed
	 * (every item created in code)
	 */
	public $protectedItemsTable = 'rights_protected';
	
	
	
	/**
	 * new table names (with prefixes)
	 */
	public $iTable; // items table
	public $iCTable; // item child table
	public $aTable; // assignments table
	
	
	
	/**
	 * 
	 */
	public function init()
	{
		parent::init();
		
		if( $this->inDebugMode )
		{
			$this->install();
		}
		else
		{
			// if not in debug mode we assume, that module is installed
			$this->isInstalled = true;
		}
		
		$this->itemTable = $this->db->prefixTable($this->itemTable);
		$this->itemChildTable = $this->db->prefixTable($this->itemChildTable);
		$this->assignmentTable = $this->db->prefixTable($this->assignmentTable);
		$this->protectedItemsTable = $this->db->prefixTable($this->protectedItemsTable);
		
		
		// create basic operation for each module named by module id
		// will be used for displaying menu items
		// any other operation should have this one as a child item.
		foreach(Yii::app()->modules as $id => $module)
		{
			$this->insertAuthItems(array(
					array(
						'id' => $id,
						'description' => "Base operation for ".$id." module. Allows user to see module's menu item.",
						'type' => 0,
					)
				)
			);
		}
	}
	
	
	
	/**
	 * install database tables
	 * check for every table before install
	 * 
	 * @return boolean
	 */
	private function install()
	{
		$this->db->schema->refresh();
		
		// check if table is already installed
		$itemTable = $this->db->prefixTable($this->itemTable);
		$itemChildTable = $this->db->prefixTable($this->itemChildTable);
		$assignmentTable = $this->db->prefixTable($this->assignmentTable);
		$protectedTable = $this->db->prefixTable($this->protectedItemsTable);
		
		$itemExist = $this->db->tableExist($itemTable);
		$itemChildExist = $this->db->tableExist($itemChildTable);
		$assignmentExist = $this->db->tableExist($assignmentTable);
		$protectedTableExist = $this->db->tableExist($protectedTable);
		//dump($itemTable); dump($itemChildTable); dump($assignmentTable);die;
		$driver = $this->db->getDriverName();
		
		// install items table
		if( !$itemExist )
		{
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->db->createCommand("
						CREATE TABLE ".$itemTable." (
							`name` varchar(64) not null,
							`type` integer not null,
							`description` text,
							`bizrule` text,
							`data` text,
							primary key (`name`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users';
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KDbAuthManager component does not work with {db} databases.',
						array('{db}'=>$driver)));
					break;
			}
			
			$command->execute();
			
			// check, if was successful
			$this->db->schema->refresh();
			if( $this->db->tableExist($itemTable) )
				$itemExist = true;
			else
				$itemExist = false;
		}
		
		
		// install item-child table
		if( !$itemChildExist )
		{
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->db->createCommand("
						CREATE TABLE `".$itemChildTable."` (
							`parent` varchar(64) not null,
							`child` varchar(64) not null,
							primary key (`parent`,`child`),
							foreign key (`parent`) references `".$itemTable."` (`name`) on delete cascade on update cascade,
							foreign key (`child`) references `".$itemTable."` (`name`) on delete cascade on update cascade
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users';
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KDbAuthManager component does not work with {db} databases.',
						array('{db}'=>$driver)));
					break;
			}
			
			$command->execute();
			
			// check, if was successful
			$this->db->schema->refresh();
			if( $this->db->tableExist($itemChildTable) )
				$itemChildExist = true;
			else
				$itemChildExist = false;
		}
		
		
		// install assignments table
		if( !$assignmentExist )
		{
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->db->createCommand("
						CREATE TABLE `".$assignmentTable."` (
							`itemname` varchar(64) not null,
							`userid` varchar(64) not null,
							`bizrule` text,
							`data` text,
							primary key (`itemname`,`userid`),
							foreign key (`itemname`) references `".$itemTable."` (`name`) on delete cascade on update cascade
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users';
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KDbAuthManager component does not work with {db} databases.',
						array('{db}'=>$driver)));
					break;
			}
			
			$command->execute();
			
			// check, if was successful
			$this->db->schema->refresh();
			if( $this->db->tableExist($assignmentTable) )
				$assignmentExist = true;
			else
				$assignmentExist = false;
		}
		
		
		// install assignments table
		if( !$protectedTableExist )
		{
			switch ($driver)
			{
				case 'mysql' :
					$command = $this->db->createCommand("
						CREATE TABLE `".$protectedTable."` (
							`itemname` varchar(64) not null,
							primary key (`itemname`),
							foreign key (`itemname`) references `".$itemTable."` (`name`) on delete restrict on update restrict
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users';
					");
					
					break;
				default :
					throw new CException(Yii::t('yii','KDbAuthManager component does not work with {db} databases.',
						array('{db}'=>$driver)));
					break;
			}
			
			$command->execute();
			
			// check, if was successful
			$this->db->schema->refresh();
			if( $this->db->tableExist($protectedTable) )
				$protectedTableExist = true;
			else
				$protectedTableExist = false;
		}
		
		
		
		
		
		
		if( !($itemExist && $itemChildExist && $assignmentExist && $protectedTableExist) )
			throw new CException(Yii::t('yii','KDbAuthManager component installation did not succeed.'));
		else
		{
			$this->isInstalled = true;
			$this->iTable = $itemTable;
			$this->iCTable = $itemChildTable;
			$this->aTable = $assignmentTable;
		}
		
		return true;
	}
	
	
	
	/**
	 * 
	 */
	public function detectLoop($itemName, $childName)
	{
		return parent::detectLoop($itemName, $childName);
	}
	
	
	
	/**
	 * 
	 */
	public function insertAuthItems($itemsArray)
	{
		if( !$this->inDebugMode )
			return false;
			
		if( !is_array($itemsArray) || empty($itemsArray) )
			return false;
	
		$hasErrors = false;
		
		// it must be an array, so lets iterate
		foreach ( $itemsArray as $key => $item )
		{
			if( !(isset( $item[ 'id' ] ) && isset( $item[ 'type' ] )) )
				continue;
			
			$id = $item['id'];
			$type = $item['type'];
			
			// validate type
			if( $type !== 0
				&& $type !== 'operation'
				&& $type !== 1
				&& $type !== 'task'
				&& $type !== 2
				&& $type !== 'role'
			)
				continue;
				
			if( $type === 'operation' ) $type = 0;
			if( $type === 'task' ) $type = 1;
			if( $type === 'role' ) $type = 2;
			
			
			// check if item with given id already exists
			$row = $this->db->createCommand()
					->select( 'name' )
					->from( $this->iTable )
					->where( "name=:n", array(':n'=>$id) )
					->queryAll();

			if( count( $row ) === 0 )
			{
				$description = (!isset($item['description'])) ? $id : $item['description'];
				$bizrule = (!isset($item['bizrule'])) ? null : $item['bizrule'];
				$data = (!isset($item['data'])) ? null : $item['data'];
				
				$this->createAuthItem($id, $type, $description, $bizrule, $data);
				$this->db->createCommand()
						->insert($this->protectedItemsTable, array('itemname' => $id));
			}
		}
	}
	
	
	
	/**
	 * adds dependancies to db
	 * 
	 * returns true or false or array with parent=>child pairs that has been not added to db
	 */
	public function insertItemChild($itemsArray)
	{
		if( !$this->inDebugMode )
			return false;
			
		if( !is_array($itemsArray) || empty($itemsArray) )
			return false;
		
		$errors = array();
		
		foreach( $itemsArray as $parent => $child )
		{
			if( !$this->hasItemChild($parent, $child) )
			{
				$result = $this->addItemChild($parent, $child);

				if( !$result )
				{
					$errors[$parent] = $child;
				}
			}
			else
			{// already added
				return true;
			}
		}
		
		if( empty($errors) )
			return true;
		else
			return $errors;
	}
}