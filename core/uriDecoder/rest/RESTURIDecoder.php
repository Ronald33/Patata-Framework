<?php
namespace core\uriDecoder\rest;

require_once(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'URIDecoder.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Helper.php');

use core\uriDecoder\URIDecoder;

class RESTURIDecoder extends URIDecoder
{
	private $_config;
	private $_exceptions;

	private static $instance;

	private function __construct($extra_configuration_path)
	{
		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path, true) : [];
		$this->_config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true), $extra_config);

		$this->checkConfigAsserts();

		header('Access-Control-Allow-Origin: ' . $this->_config['ACA']['ORIGIN']);
		header('Access-ContrFol-Allow-Headers: ' . $this->_config['ACA']['HEADERS']);

		$this->_exceptions = isset($this->_config['EXCEPTIONS']) ? explode(',', $this->_config['EXCEPTIONS']) : [];

		$this->execute();
	}

	private function checkConfigAsserts()
	{
		if(isset($this->_config['EXCEPTIONS'])) { assert(is_string($this->_config['EXCEPTIONS']), 'In RESTURIDecoder, EXCEPTIONS is invalid'); }
		assert(ctype_alnum($this->_config['REST_DEFAULT_CLASS']), 'In RESTURIDecoder, REST_DEFAULT_CLASS is invalid');
		assert(ctype_alnum($this->_config['EXCEPTIONS_DEFAULT_METHOD']), 'In RESTURIDecoder, EXCEPTIONS_DEFAULT_METHOD is invalid');
		assert(ctype_alnum($this->_config['METHODS']['GET']), 'In RESTURIDecoder, METHODS[GET] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['POST']), 'In RESTURIDecoder, METHODS[POST] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['PUT']), 'In RESTURIDecoder, METHODS[PUT] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['DELETE']), 'In RESTURIDecoder, METHODS[DELETE] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['OPTIONS']), 'In RESTURIDecoder, METHODS[OPTIONS] is invalid');
		assert(ctype_alnum($this->_config['METHODS']['PATCH']), 'In RESTURIDecoder, METHODS[PATCH] is invalid');
		assert(is_string($this->_config['ACA']['ORIGIN']), 'In RESTURIDecoder, ACA[ORIGIN] is invalid');
		assert(is_string($this->_config['ACA']['HEADERS']), 'In RESTURIDecoder, ACA[HEADERS] is invalid');
	}

	public static function getInstance($extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new RESTURIDecoder($extra_configuration_path); }
		return self::$instance;
	}

	public function execute()
	{
		$parts = parent::getRequestParts();

		$this->_class = sizeof($parts) > 0 ? array_shift($parts) : $this->_config['REST_DEFAULT_CLASS'];

		if(in_array($this->_class, $this->_exceptions))
		{
			$this->_method = sizeof($parts) > 0 ? array_shift($parts) : $this->_config['EXCEPTIONS_DEFAULT_METHOD'];
		}
		else
		{
			if(isset($_GET['PATATA_REST_METHOD']) && $_SERVER['REQUEST_METHOD'] != 'OPTIONS')
			{
				$this->_method = $this->_config['METHODS'][$_GET['PATATA_REST_METHOD']];
			}
			else { $this->_method = $this->_config['METHODS'][$_SERVER['REQUEST_METHOD']]; }
		}

		$this->_arguments = $parts;
	}

	public function getMethods() { return $this->_config['METHODS']; }

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
