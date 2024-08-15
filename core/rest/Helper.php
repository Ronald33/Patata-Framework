<?php
namespace core\rest;

abstract class Helper
{
    public static function getArrayFromString($value)
    {
        $result = [];
		$elements = explode(',', $value);
		foreach($elements as $element)
		{
			$cleaned = trim($element);
			if(strlen($cleaned) > 0) { array_push($result, $cleaned); }
		}
		
		return $result;
    }

    public static function getIps($value)
    {
        $result = [];
        
		$elements = explode(',', $value);
		foreach($elements as $element)
		{
			$cleaned = trim($element);
			if(strlen($cleaned) > 0)
            {
                $transformed = gethostbyname($cleaned);
                if(filter_var($transformed, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) { array_push($result, $transformed); }
            }
		}
		
		return $result;
    }
}