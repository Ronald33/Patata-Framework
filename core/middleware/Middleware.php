<?php
namespace core\middleware;

require_once(PATH_BASE . '/core/uriDecoder/URIDecoder.php');
use core\uriDecoder\uriDecoder;

class Middleware
{
	private static $instance;

	private function __construct()
	{

	}

	public static function getInstance()
	{
		if(self::$instance == NULL) { self::$instance = new Middleware(); }
		return self::$instance;
	}

	public function execute(URIDecoder $uriDecoder)
	{
		$class = $uriDecoder->getClass();
	    $method = $uriDecoder->getMethod();
		$arguments = $uriDecoder->getArguments();
		
		/* REST */
		$rest = $uriDecoder->getRest();
		if($rest)
		{
			$rest->auth($class);

			/* REST exceptions */
			if($rest->getData() == 'usuario-login') // Access with token from config
			{
				$is_usuario_login = $class == 'Usuario' && $method == 'get' && isset($_GET['usuario']) && isset($_GET['contrasenha']);
				if(!$is_usuario_login) { \Error\Error::showMessage('Usuario no autorizado', '', 401); die(); }
			}
			if($rest->getData() == 'cliente') // Access with token from config
			{
				$alloweds = array('Producto.get', 'Reserva.post');
				if(!in_array($class . '.' . $method, $alloweds)) { \Error\Error::showMessage('Usuario no autorizado', '', 401); die(); }
			}
			/* End REST exceptions */
		}
		/* Auth REST */
	}

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
