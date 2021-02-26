<?php
namespace core\rest;

require_once(PATH_BASE . '/core/IToken.php');
require_once(PATH_BASE . '/core/IError.php');

use core\IError;
use core\IToken;

class REST
{
	private $config;
	private $data;
	private $token;
	private $error;
	private static $instance;
	private static $path_config = __DIR__ . '/config.ini';
	
	private function __construct() { $this->config = parse_ini_file(self::$path_config, true); }

	public static function getInstance()
	{
		if(self::$instance == NULL) { self::$instance = new REST(); }
		return self::$instance;
	}
	
	public function setError(IError $error) { $this->error = $error; }
	public function setToken(IToken $token) { $this->token = $token; }
	public function getToken() { return $this->token; }

	public function auth($class)
	{
		if($_SERVER['REQUEST_METHOD'] == 'OPTIONS' || in_array($class, $this->getClassExceptions())) { return true; }

		$token = NULL;
		$headers = apache_request_headers();
		if(isset($headers['authorization'])) { $token = $headers['authorization']; }
		else if(isset($headers['Authorization']))  { $token = $headers['Authorization']; }

		if($token)
		{
			if($this->validateTokenFromConfig($token)) { $this->data = $token; }
			else
			{
				$data = $this->token->decode($token);
				if($data) { $this->data = $data; }
				else { $this->error->showMessage('Token desconocido', '', 401); }
			}
		}
		else
		{
			if(isset($this->config['skip_auth']))
			{
				$skip_auth = preg_split('/,/', $this->config['skip_auth'], NULL, PREG_SPLIT_NO_EMPTY);
				$ips = array_map('gethostbyname', $skip_auth);
				if(in_array($_SERVER['REMOTE_ADDR'], $ips)) { $this->data = 'SKIP-AUTH'; }
			}
			else { $this->error->showMessage('Usuario no autorizado', '', 401); }
		}
	}
	
	public function validateTokenFromConfig($token)
	{
		if(isset($this->config['special_tokens']) && isset($this->config['special_tokens'][$token]))
		{
			// $special_tokens = explode(',', $this->config['special_tokens'][$token]);
			$special_tokens_list = $this->config['special_tokens'][$token];
			
			if(empty($special_tokens_list)) { return true; }
			else
			{
				$special_tokens = explode(',', $special_tokens_list);
				$ips = array_map('gethostbyname', $special_tokens);
				if(in_array($_SERVER['REMOTE_ADDR'], $ips)) { return true; }
			}
		}
		else { return false; }
	}

	public function getData() { return $this->data; }
	public function getMethods() { return $this->config['methods']; }
	public function getClassExceptions()
	{
		if(isset($this->config['class_exceptions']))
		{
			return preg_split('/,/', $this->config['class_exceptions'], NULL, PREG_SPLIT_NO_EMPTY);
		}
		else { return []; }
	}
	public function getAllowedMethodsFromClass($class)
	{
		$allowed = array();
		$rest_methods = $this->config['methods'];
		foreach ($rest_methods as $key => $value) { if(is_callable(array('\\' . $class, $value))) { array_push($allowed, $key); } }
		return implode(', ', $allowed);
	}

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
