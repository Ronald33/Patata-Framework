<?php
namespace core\uriDecoder;

assert(http_response_code() != FALSE, 'CLI no supported');

abstract class URIDecoder
{
	protected $_class;
	protected $_method;
	protected $_arguments;

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

	/*
		Example: http://localhost/my_project/a/b/c/d/e
		Return: [a, b, c, d, e]
	*/
	protected function getRequestParts()
	{
		$uri_separator = '/';
		// Limpiamos los parametros enviados via GET
		$pos_qm = strpos($_SERVER['REQUEST_URI'], '?');
		$request_uri = $pos_qm ? substr($_SERVER['REQUEST_URI'], 0, $pos_qm) : $_SERVER['REQUEST_URI'];

		$folder = self::getFolder();
		$folder_length = strlen($folder);

		// Obtenemos la URI limpiando los URI_SEPARATOR y removiendo el directorio contenedor
		$request = trim(substr($request_uri, $folder_length), $uri_separator);

		if($request == '') { return []; }

		return explode($uri_separator, $request);
	}

	public function getClass() { return $this->_class; }
	public function getMethod() { return $this->_method; }
	public function getArguments() { return $this->_arguments; }
}
