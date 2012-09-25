<?php
/**
 * This ApplicationComponent class should be used for every component which uses
 * database connection.
 * This class provides basic functions and setters for component - database relations.
 * 
 */
class KDbConnection extends CDbConnection
{
	/**
	 * Checks if database table exists.
	 * Database connection must be set for component which calls this function
	 */
	public function tableExist($tableName)
	{
		// get tables names
		$tables = $this->schema->tableNames;
		
		return in_array($tableName, $tables);
	}
	
	
	/**
	 * returns table name with prefix
	 */
	public function prefixTable($tableName)
	{
		if($this->tablePrefix!==null)
			return $this->tablePrefix . $tableName;
		else
			return $tableName;
	}
}