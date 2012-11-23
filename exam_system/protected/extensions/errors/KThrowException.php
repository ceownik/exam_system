<?php

/**
 * 
 */
class KThrowException
{
	public static function throw404($message = null)
	{
		if($message == null)
		{
			$message = 'Bad address.';
		}
		
		throw new CHttpException(404, $message);
	}
	
	public static function throw500($message = null)
	{
		if($message == null)
		{
			$message = 'Internal server error.';
		}
		
		throw new CHttpException(500, $message);
	}
}
