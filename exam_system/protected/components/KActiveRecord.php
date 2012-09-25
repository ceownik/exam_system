<?php
/**
 * KActiveRecord
 *
 * @author kk
 */
class KActiveRecord extends CActiveRecord 
{
	/**
	 * shortcut to message translator 
	 */
	public $t = null;
	
	
	
	/**
	 * prefix for database tables
	 */
	public $tablePrefix = '';
	
	
	
	/**
	 * 
	 */
	public function init()
	{
		parent::init();
		
		$this->t = Yii::app()->messages;
		
		$this->tablePrefix = Yii::app()->db->tablePrefix;
	}
}