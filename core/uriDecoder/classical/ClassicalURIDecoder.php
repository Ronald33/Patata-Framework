<?php
namespace core\uriDecoder\classical;

require_once(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'URIDecoder.php');

use core\uriDecoder\URIDecoder;

class ClassicalURIDecoder extends URIDecoder
{
	private $_config;

	private static $instance;

	private function __construct($extra_configuration_path)
	{
		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path) : [];
		$this->_config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'), $extra_config);

		$this->checkConfigAsserts();

		$this->_class = $this->_config['CLASSICAL_DEFAULT_CLASS'];
		$this->_method = $this->_config['CLASSICAL_DEFAULT_METHOD'];
		$this->_arguments = [];

		$this->execute();
	}

	private function checkConfigAsserts()
	{
		assert(ctype_alnum($this->_config['CLASSICAL_DEFAULT_CLASS']), 'In Classical URIDecoder, CLASSICAL_DEFAULT_CLASS is invalid');
		assert(ctype_alnum($this->_config['CLASSICAL_DEFAULT_METHOD']), 'In Classical URIDecoder, CLASSICAL_DEFAULT_METHOD is invalid');
	}

	public static function getInstance($extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new ClassicalURIDecoder($extra_configuration_path); }
		return self::$instance;
	}

	public function execute()
	{
		$parts = parent::getRequestParts();

		if(sizeof($parts) > 0)
		{
			$this->_arguments = $parts;
			$this->_class = array_shift($this->_arguments);
			if($this->_arguments) { $this->_method = array_shift($this->_arguments); }
		}
	}

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
