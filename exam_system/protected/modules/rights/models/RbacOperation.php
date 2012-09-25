<?php

/**
 * This is the model class for rbac operations
 *
 * The followings are the available columns in table 'rbac_auth_item':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 */
class RbacOperation extends RbacAuthItem {

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RbacAuthItem the static model class
	 */
	public static function model( $className = __CLASS__ )
	{
		return parent::model( $className );
	}

	public function defaultScope()
	{
		return array(
			'condition' => "type=0"
		);
	}

}