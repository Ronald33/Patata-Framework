<?php
namespace core\uriDecoder;

require_once(PATH_BASE . '/core/rest/REST.php');

use core\rest\REST;

class URIDecoder
{
	private $config;
	private $class;
	private $method;
	private $arguments;
	private $rest;
	private static $instance;
	private static $path_config = __DIR__ . '/config.ini';
	
	private function __construct()
	{
		$this->config = parse_ini_file(self::$path_config);
		$this->class = $this->config['DEFAULT_CLASS'];
		$this->method = $this->config['DEFAULT_METHOD'];
		$this->arguments = [];
	}

	public static function getInstance()
	{
		if(self::$instance == NULL) { self::$instance = new URIDecoder(); }
		return self::$instance;
	}

	public function setRest(REST $rest) { $this->rest = $rest; }
	public function getRest() { return $this->rest; }
	
	public function execute()
	{
		$uri_separator = $this->config['URI_SEPARATOR'];
		
		// Limpiamos los parametros enviados via GET
		$pos_qm = strpos($_SERVER['REQUEST_URI'], '?');
		$request_uri = $pos_qm ? substr($_SERVER['REQUEST_URI'], 0, $pos_qm) : $_SERVER['REQUEST_URI'];
		
		$folder = self::getFolder();
		$folder_length = strlen($folder);

		// Obtenemos la URI limpiando los URI_SEPARATOR y removiendo el directorio contenedor
		$this->uri = trim(substr($request_uri, $folder_length), $uri_separator);

		if($this->uri) // Si existe una URI
		{
			$this->arguments = explode($uri_separator, $this->uri); // Dividimos la URI en partes segun el URI_SEPARATOR
			if($this->arguments)
			{
				$this->class = array_shift($this->arguments);

				if(isset($this->rest) && !in_array($this->class, $this->rest->getClassExceptions()))
				{
					if($_SERVER['REQUEST_METHOD'] != 'OPTIONS' && isset($_GET['PATATA_REST_METHOD']))
					{
						$this->method = $this->rest->getMethods()[$_GET['PATATA_REST_METHOD']];
					}
					else { $this->method = $this->rest->getMethods()[$_SERVER['REQUEST_METHOD']]; }
				}
				else { if($this->arguments) { $this->method = array_shift($this->arguments); } }
			}
		}
	}

	public function getClass() { return $this->class; }
	public function getMethod() { return $this->method; }
	public function getArguments() { return $this->arguments; }
	
	/* Helpers */
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
	/* End Helpers */

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
