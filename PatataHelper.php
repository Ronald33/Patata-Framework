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
        return $protocol . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . self::getFolder();
    }

    public static function getAllowedMethodsFromClass(ReflectionClass $refClass)
	{
        $methods = Repository::getURIDecoder()->getMethods();
		$allowed = [];
        if(is_array($methods))
        {
            foreach($methods as $key => $value)
            {
				if($refClass->hasMethod($value)) { array_push($allowed, $key); }
            }
        }
		return implode(', ', $allowed);
	}

	public static function getCustomConfig() { return parse_ini_file(CUSTOM_CONFIG_PATH, true); }

	public static function isPositiveInteger($value) { return filter_var($value, FILTER_VALIDATE_INT) !== false && (int)$value > 0; }


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

	public static function getObjectFromToken($token)
	{
		return json_decode(Repository::getREST()->getToken()->decode($token)['serialized']);
	}

	public static function getCurrentTimestamp($timezone = TIMEZONE)
	{
		$now = new DateTime('', new DateTimeZone($timezone));
		return $now->getTimestamp();
	}

	public static function getDateFormattedFromTimestamp($timestamp, $format, $timezone = TIMEZONE)
	{
		$date = new DateTime('', new DateTimeZone($timezone));
		$date->setTimestamp($timestamp);
		return $date->format($format);
	}

	public static function getDateFromTimestamp($timestamp, $timezone = TIMEZONE)
	{
		return self::getDateFormattedFromTimestamp($timestamp, 'Y-m-d', $timezone);
	}

	public static function getDateTimeFromTimestamp($timestamp, $timezone = TIMEZONE)
	{
		return self::getDateFormattedFromTimestamp($timestamp, 'Y-m-d H:i:s', $timezone);
	}

	private static function getDataForRender($data, $clear_missing_values, $add_extra_data)
	{
		assert(is_array($data));

		$extra = 
		[
			'TITLE' => 'Patata FW', 
			'DESCRIPTION' => 'Patata Framework', 
			'URL_BASE' => URL_BASE
		];

		if($add_extra_data) { $data = array_merge($extra, $data); }

		$results = 
		[
			'search' => [], 
			'replace' => []
		];

		foreach($data as $key => $value)
		{
			array_push($results['search'], '/{{' . $key . '}}/');
			array_push($results['replace'], $value);
		}

		if($clear_missing_values)
		{
			array_push($results['search'], '/{{.+}}/');
			array_push($results['replace'], '');
		}

		return $results;
	}

	public static function getTemplateRendered($template, $data, $clear_missing_values = true, $add_extra_data = true)
	{
		$data = self::getDataForRender($data, $clear_missing_values, $add_extra_data);
		return preg_replace($data['search'], $data['replace'], $template);
	}
}
