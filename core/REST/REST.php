<?php
namespace REST;
require_once('core/REST/Token/Token.php');

abstract class REST
{
	private static $data = NULL;

	public static function auth()
	{
		if($_SERVER['REQUEST_METHOD'] != 'OPTIONS')
		{
			if(isset(apache_request_headers()['authorization']))
			{
				$token = apache_request_headers()['authorization'];
				if(self::validateTokenFromConfig($token)) { self::setData($token); }
				else
				{
					$data = REST::getDataFromToken($token);
					if($data) { REST::setData($data); }
					else { \Error\Error::showMessage('Token desconocido', '', 401); die(); }
				}
			}
			else
			{
				$config = parse_ini_file('core/REST/config/config.ini', true);
				if(isset($config['skip_auth']))
				{
					$skip_auth = preg_split('/,/', $config['skip_auth'], NULL, PREG_SPLIT_NO_EMPTY);
					$ips = array_map('gethostbyname', $skip_auth);
					if(in_array($_SERVER['REMOTE_ADDR'], $ips)) { REST::setData('SKIP-AUTH'); }
				}
				else { \Error\Error::showMessage('Usuario no autorizado', '', 401); die(); }
			}
		}
		return true;
	}

	public static function getAllowedMethodsFromClass($class)
	{
		$allowed = array();
		$rest_methods = parse_ini_file('core/REST/config/config.ini', true)['methods'];
		foreach ($rest_methods as $key => $value) { if(is_callable(array('\\' . $class, $value))) { array_push($allowed, $key); } }
		return implode(', ', $allowed);
	}

	public static function getMethods() { return parse_ini_file('core/REST/config/config.ini', true)['methods']; }
	public static function getClassExceptions()
	{
		$config = parse_ini_file('core/REST/config/config.ini', true);
		if(isset($config['class_exceptions'])) { return preg_split('/,/', $config['class_exceptions'], NULL, PREG_SPLIT_NO_EMPTY); }
		else { return []; }
	}
	public static function getToken($data) { return Token::getToken($data); }
	public static function getDataFromToken($token) { return Token::getDataFromToken($token); }
	public static function getData() { return self::$data; }
	public static function validateTokenFromConfig($token)
	{
		$config = parse_ini_file('core/REST/config/config.ini', true);
		if(isset($config['special_tokens']) && isset($config['special_tokens'][$token]))
		{
			$special_token = $config['special_tokens'][$token];
			if(empty($special_token)) { return true; }
			else
			{
				$ips = array_map('gethostbyname', $special_token);
				if(in_array($_SERVER['REMOTE_ADDR'], $ips)) { return true; }
			}
		}
		else { return false; }
	}
	private static function setData($data) { self::$data = $data; }
}
