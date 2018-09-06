<?php
abstract class Helper
{
	public static function respondWithJSON($message, $code)
	{
		http_response_code($code);
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($message, JSON_NUMERIC_CHECK);
	}

	public static function convertDateToMysqlFormat($date)
	{
		$date = DateTime::createFromFormat('d/m/Y', $date);
		return $date->format('Y-m-d');
	}

	private static function setProperty($destinationReflection, $name, &$destination, $value)
	{
		$propDest = $destinationReflection->getProperty($name);
		$propDest->setAccessible(true);
		$propDest->setValue($destination, $value);
	}

	/**
	 * Class casting
	 *
	 * @param string|object $destination
	 * @param object $sourceObject
	 * @return object
	 */
	public static function cast($destination, $sourceObject)
	{
		if(is_string($destination)) { $destination = new $destination(); }
		$sourceReflection = new ReflectionObject($sourceObject);
		$destinationReflection = new ReflectionObject($destination);
		$sourceProperties = $sourceReflection->getProperties();

		foreach($sourceProperties as $sourceProperty)
		{
			$sourceProperty->setAccessible(true);
			$name = $sourceProperty->getName();
			$pname = '_' . $name;
			$value = $sourceProperty->getValue($sourceObject);

			if($destinationReflection->hasProperty($pname)) // For private attributes
			{
				self::setProperty($destinationReflection, $pname, $destination, $value);
			}
			else
			{
				if($destinationReflection->hasProperty($name))
				{
					self::setProperty($destinationReflection, $name, $destination, $value);
				}
			else { /*$destination->$name = $value;*/ }
			}
		}
		return $destination;
	}

	public static function getInputs()
    {
        return json_decode(file_get_contents('php://input'));
    }

	/*public static function myFunction()
	{
		
	}*/
}