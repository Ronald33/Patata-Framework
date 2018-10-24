<?php
namespace URIDecoder;

require_once('core/URIDecoder/config/config.php');
require_once('core/URIDecoder/Helper.php');

class URIDecoder
{
	public function __construct()
	{
		$this->class = \CLASS_DEFAULT;
		$this->method = \METHOD_DEFAULT;
		$this->arguments = array();
		
		// Limpiamos los parametros enviados via GET
		$pos_qm = strpos($_SERVER['REQUEST_URI'], '?');
		$request_uri = $pos_qm ? substr($_SERVER['REQUEST_URI'], 0, $pos_qm) : $_SERVER['REQUEST_URI'];

		// Obtenemos la URI limpiando los URI_SEPARATOR y removiendo el directorio contenedor
		$this->uri = trim(substr($request_uri, Helper::getFolderLength()), URI_SEPARATOR);

		if($this->uri) // Si existe una URI
		{
			$this->arguments = explode(URI_SEPARATOR, $this->uri); // Dividimos la URI en partes segun el URI_SEPARATOR
			if($this->arguments)
			{
				$this->class = array_shift($this->arguments);
				if(\ENABLE_REST && !in_array($this->class, \REST_CLASS_EXCEPTIONS))
				{
					$this->method = \REST_METHOD[$_SERVER['REQUEST_METHOD']];
				}
				else { if($this->arguments) { $this->method = array_shift($this->arguments); } }
			}
		}
	}
	
	public function getClass() { return $this->class; }
	public function getMethod() { return $this->method; }
	public function getArguments() { return $this->arguments; }
}
