<?php
abstract class Helper
{
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

	public static function getBodyRequest()
    {
        return json_decode(file_get_contents('php://input'));
    }

	public static function getCurrentTimestamp($timezone = 'America/Lima')
	{
		$now = new DateTime('', new DateTimeZone($timezone));
		return $now->getTimestamp() * 1000;
	}

	public static function getDateTimeFromTimestamp($timestamp, $timezone = 'America/Lima')
	{
		$date = new DateTime('', new DateTimeZone($timezone));
		$date->setTimestamp($timestamp / 1000);
		return $date->format('Y-m-d H:i:s');
	}

	public static function getImagenFromBase64Image($base64Image)
	{
		$parts = explode(',', $base64Image);
		$image = $parts[1];
		return base64_decode($image);
	}

	public static function deleteFile($path)
	{
		if(file_exists($path) && !@unlink($path)) { throw new Exception('El archivo no pudo ser eliminado'); }
	}

	/*public static function myFunction()
	{

	}*/
}
