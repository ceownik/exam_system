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
}
