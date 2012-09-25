<?php
/**
 * KButtonColumn class file.
 *
 * @author Kamil Kafara
 */

Yii::import('zii.widgets.grid.CButtonColumn');

/**
 * KButtonColumn extends CButtonColumn and adds some extra functionality
 *
 */
class KButtonColumn extends CButtonColumn
{
	
	// override function that creates button 
	// add extra functionality for submit buttons
	protected function renderButton($id,$button,$row,$data)
	{	
		if( isset($button['options']) && is_array($button['options']) && !empty($button['options']) )
		{
			$options = $button['options'];
			
			// prepare extra options
			if( isset($options['params']) )
			{
				foreach( $options['params'] as $key => $param )
				{
					if( $this->evaluateExpression($options['params'][$key],array('row'=>$row,'data'=>$data)) )
					{
						$options['params'][$key] = $this->evaluateExpression($options['params'][$key],array('row'=>$row,'data'=>$data));
					}
					else
					{
						return;
					}
				}
			}
  			//return;
			
			$button['options'] = $options;
		}
		
		parent::renderButton($id, $button, $row, $data);
	}

	
}
