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
			if(isset(apache_request_headers()['Authorization']))
			{
				$token = apache_request_headers()['Authorization'];
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
				$skip_auth = parse_ini_file('core/REST/config/config.ini', true)['skip_auth'];
				if(isset($skip_auth['webs']))
				{
					$ips = array_map('gethostbyname', $skip_auth['webs']);
					if(in_array($_SERVER['SERVER_ADDR'], $ips)) { REST::setData('WEB-ALLOWED'); }
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
	public static function getClassExceptions() { return parse_ini_file('core/REST/config/config.ini', true)['class_exceptions']; }
	public static function getToken($data) { return Token::getToken($data); }
	public static function getDataFromToken($token) { return Token::getDataFromToken($token); }
	public static function getData() { return self::$data; }
	public static function validateTokenFromConfig($token)
	{
		$config = parse_ini_file('core/REST/config/config.ini', true);

		if(isset($config['token_by_ip']) && isset($config['token_by_ip'][$token]))
		{
			$ips = array_map('gethostbyname', $config['token_by_ip'][$token]);
			if(in_array($_SERVER['SERVER_ADDR'], $ips)) { return true; }
		}
		else { return false; }
	}
	private static function setData($data) { self::$data = $data; }
}
