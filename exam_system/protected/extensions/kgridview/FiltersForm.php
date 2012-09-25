<?php

class FiltersForm extends CFormModel
{
	
	   public $filters = array();
 
    /**
     * Override magic getter for filters
     */
    public function __get($name)
    {
        if(!array_key_exists($name, $this->filters))
            $this->filters[$name] = null;
		
		//dump($this->filters);
		//if(isset($this->filters['myTask'])) dump((bool)$this->filters['myTask']);
        return $this->filters[$name];
    }
	
	
	
	/**
	* Filter input array by key value pairs
	* @param array $data rawData
	* @return array filtered data array
	*/
	public function filter(CArrayDataProvider $data)
	{
		$temp = $data->getData();

		foreach ($temp AS $index => $item)
		{
			foreach ($this->filters AS $key => $value)
			{
				if($value == '') continue; // bypass empty filter

				$test = false;  // value to test for

				if($item instanceof CModel)
				{
					if(isset($item->$key) == false ) throw new CException("Property ".get_class($item)."::{$key} does not exist!");
						$test = $item->$key;
				}
				elseif(is_array($item))
				{
					if(!array_key_exists($key, $item)) throw new CException("Key {$key} does not exist in Array!");
						$test = $item[$key];
				}
				else
					throw new CException("Data in CArrayDataProvider must be an array of arrays or CModels!");
				
				if(is_bool($test))
				{
					if($test !== (bool)$value)
						unset($temp[$index]);
				}
				elseif(stripos($test, $value) === false)
					unset($temp[$index]);
			}
		}

		$data->setData(array_values($temp));
		return $data;
	}
}