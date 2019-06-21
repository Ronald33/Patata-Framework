<?php
namespace REST;
abstract class REST
{
	private static $data = NULL;
	
	public static function auth()
	{
		if(isset(apache_request_headers()['Authorization']))
		{
			$token = apache_request_headers()['Authorization'];
			$data = REST::getDataFromToken($token);
			if($data == false) { REST::setData($data); }
			else { \Error\Error::showMessage('Token desconocido', '', 401); die(); }
		}
		else
		{
			if(!in_array($_SERVER['SERVER_ADDR'], parse_ini_file('core/REST/config/config.ini', true)['skip_auth']['webs']))
			{
				\Error\Error::showMessage('Usuario no autorizado', '', 401);
				die();
			}
		}
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
	public static function getTokenMaster() { return parse_ini_file('core/REST/config/config.ini', true)['skip_auth']['token_master']; }
}
