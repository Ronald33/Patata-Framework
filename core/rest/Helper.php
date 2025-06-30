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

    public static function getRanges($value)
    {
        $result = [];
        
		$elements = explode(',', $value);
		foreach($elements as $element)
		{
			$cleaned = trim($element);
			if(strlen($cleaned) > 0)
            {
                $transformed = Helper::getRange($cleaned);
				assert($transformed != false, 'In REST, the value: ' . $cleaned . ' is invalid');
                array_push($result, $transformed);
            }
		}
		
		return $result;
    }

	private static function getRange($input)
	{
		// Verificar si la entrada es una dirección IP válida
		if(filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) { return $input . '/32'; }
	
		// Verificar si la entrada es un rango CIDR válido
		if(preg_match('/^(\d{1,3}\.){3}\d{1,3}\/\d{1,2}$|^\/\d{1,2}$/', $input))
		{
			if(strpos($input, '/') == 0)
			{
				$ip = NULL;
				// Si solo hay una máscara
				$mask = substr($input, 1);
			}
			else
			{
				// Si la IP está presente, separar la IP y la máscara
				list($ip, $mask) = explode('/', $input);
				
				// Validar que la IP es válida
				assert(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false, 'Invalid IP address in CIDR');
			}
		
			// Validar que la máscara está en el rango correcto
			assert($mask >= 0 && $mask <= 32, 'Invalid CIDR mask');

			return ($ip == NULL) ? $_SERVER['SERVER_ADDR'] . $input : $input;
		}
	
		// Intentar convertir la entrada a una dirección IP usando gethostbyname
		$resolved_ip = gethostbyname($input);

		if($input != $resolved_ip) { return $resolved_ip . '/32'; }
	
		return false;
	}

	public static function ipIsInRange($ip, $ranges)
	{
		$ip = ip2long($ip);

		foreach($ranges as $range)
		{
			list($subnet, $bits) = explode('/', $range);
			$subnet = ip2long($subnet);
			$mask = -1 << (32 - $bits);
			$subnet &= $mask;
			if(($ip & $mask) == $subnet) { return true; };
		}

		return false;
	}
}