<?php

Yii::import('CVarDumper');

/**
 * extend default calss for variable dumping
 */
class KDump extends CVarDumper
{
	public static function d($var, $die=false, $depth = 100, $higlight = true)
	{
		echo '<div style="
				display: block;
				width: 98%;
				overflow: auto;
				border: 2px solid #dd3333;
				padding: 5px;
				background-color: #fff;
				margin: 2px auto;
			"><pre>';
		self::dump($var, $depth, $higlight);
		echo '</pre></div>';
		if($die)
			die;
	}
}
