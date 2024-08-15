<?php
abstract class PatataHelper
{
    /*
	Example: http://localhost/my_project
	Return: my_project
    */
    private static function getFolder()
    {
        $self = $_SERVER['PHP_SELF'];
        $folder = dirname($self);
        if($folder == '/') { return ''; }
        else { return $folder; }
    }

    public static function getURLBase()
    {
        assert(http_response_code() != FALSE, 'CLI no supported');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . self::getFolder();
    }

    public static function getAllowedMethodsFromClass($class)
	{
        $rest = Repository::getREST();
        $methods = $rest->getMethods();
		$allowed = [];
        if(is_array($methods))
        {
            foreach ($methods as $key => $value)
            {
                if(is_callable(array('\\' . $class, $value))) { array_push($allowed, $key); }
            }
        }
		return implode(', ', $allowed);
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

	public static function getPayload()
    {
        return json_decode(file_get_contents('php://input'));
    }

	public static function getTokenFromObject($object)
	{
		$data = ['serialized' => serialize($object)];
		return Repository::getREST()->getToken()->encode($data);
	}

	public static function getObjectFromToken($token)
	{
		return unserialize(Repository::getREST()->getToken()->decode($token)['serialized']);
	}

	public static function getCurrentUser()
	{
		$token = Repository::getREST()->getTokenFromRequest();
		return self::getObjectFromToken($token);
	}

	public static function getCurrentTimestamp($timezone = TIMEZONE)
	{
		$now = new DateTime('', new DateTimeZone($timezone));
		return $now->getTimestamp() * 1000;
	}

	public static function getDateFromTimestamp($timestamp, $timezone = TIMEZONE)
	{
		$date = new DateTime('', new DateTimeZone($timezone));
		$date->setTimestamp($timestamp);
		return $date->format('Y-m-d');
	}

	public static function getDateTimeFromTimestamp($timestamp, $timezone = TIMEZONE)
	{
		$date = new DateTime('', new DateTimeZone($timezone));
		$date->setTimestamp($timestamp);
		return $date->format('Y-m-d H:i:s');
	}
}