<?php
function encrypt( $value )
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

	
echo encrypt('1234');