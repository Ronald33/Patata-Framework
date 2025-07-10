<?php
namespace core\uriDecoder\rest;
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
}