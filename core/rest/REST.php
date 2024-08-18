<?php
namespace core\rest;

require_once(__DIR__ . '/Helper.php');

use core\rest\token\IToken;
use Exception;

class REST
{
	private $_config;
	private $_data;
	private $_token;
	private $_dataIsDecodable;
	private static $instance;
	
	private function __construct($extra_configuration_path)
	{
		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path, true) : [];
        $this->_config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true), $extra_config);

		$this->checkConfigAsserts();

		$this->_dataIsDecodable = false;
	}

	private function checkConfigAsserts()
	{
		if(isset($this->_config['CLASS_EXCEPTIONS'])) { assert(is_string($this->_config['CLASS_EXCEPTIONS']), 'In REST, CLASS_EXCEPTIONS is invalid'); }
		if(isset($this->_config['SKIP_AUTH'])) { assert(is_string($this->_config['SKIP_AUTH']), 'In REST, SKIP_AUTH is invalid'); }
		assert(is_array($this->_config['SPECIAL_TOKENS']), 'In REST, SPECIAL_TOKENS must be an array');
		assert(ctype_alnum($this->_config['METHODS']['GET']), 'In REST, METHODS[GET] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['POST']), 'In REST, METHODS[POST] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['PUT']), 'In REST, METHODS[PUT] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['DELETE']), 'In REST, METHODS[DELETE] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['OPTIONS']), 'In REST, METHODS[OPTIONS] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['PATCH']), 'In REST, METHODS[PATCH] is invalid');
	}

	public static function getInstance($extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new REST($extra_configuration_path); }
		return self::$instance;
	}

	public function setToken(IToken $token) { $this->_token = $token; }

	public function auth($class)
	{
		if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { return true; }
		if(isset($this->_config['CLASS_EXCEPTIONS']) && in_array($class, Helper::getArrayFromString($this->_config['CLASS_EXCEPTIONS']))) { return true; }

		$token = $this->getTokenFromRequest();

		if($token)
		{
			if($this->validateTokenFromConfig($token)) { $this->_data = $token; }
			else
			{
				try { $this->_data = $this->_token->decode($token);	$this->_dataIsDecodable = true; }
				catch(Exception $e) { return false; }
			}
		}
		else
		{
			if(isset($this->_config['SKIP_AUTH']))
			{
				$ips = Helper::getRanges($this->_config['SKIP_AUTH']);
				if(Helper::ipIsInRange($_SERVER['REMOTE_ADDR'], $ips)) { $this->_data = 'SKIP-AUTH'; }
				else { return false; }
			}
			else { return false; }
		}

		return true;
	}
	
	public function validateTokenFromConfig($token)
	{
		if(isset($this->_config['SPECIAL_TOKENS']) && isset($this->_config['SPECIAL_TOKENS'][$token]))
		{
			$ips = Helper::getRanges($this->_config['SPECIAL_TOKENS'][$token]);
			if(Helper::ipIsInRange($_SERVER['REMOTE_ADDR'], $ips)) { return true; }
		}
		else { return false; }
	}

	public function getData() { return $this->_data; }
	public function getTokenFromRequest()
	{
		$token = NULL;
		$headers = apache_request_headers();
		if(isset($headers['authorization'])) { $token = $headers['authorization']; }
		else if(isset($headers['Authorization']))  { $token = $headers['Authorization']; }
		return $token;
	}

	public function getMethods() { return $this->_config['METHODS']; }
	public function getToken() { return $this->_token; }
	public function dataIsDecodable() { return $this->_dataIsDecodable; }

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
