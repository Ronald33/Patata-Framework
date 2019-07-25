<?php
use UriDecoder\URIDecoder;
use \REST\REST;

abstract class Middleware
{
	public static function executePreURIDecoder()
	{
		/* REST */
		if(\ENABLE_REST)
		{
			require_once('core/REST/REST.php');
			require_once('core/REST/Response/Response.php');
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Headers: *');
			REST::auth();
		}
		/* END REST */
	}

	public static function executePreCaller(URIDecoder $URIDecoder)
	{
		$class = $URIDecoder->getClass();
	    $method = $URIDecoder->getMethod();
	    $arguments = $URIDecoder->getArguments();

		/* REST */
		if(REST::getData() == 'usuario-login') // Access with token from config
		{
			$is_usuario_login = $class == 'Usuario' && $method == 'get' && isset($_GET['usuario']) && isset($_GET['contrasenha']);
			if(!$is_usuario_login) { \Error\Error::showMessage('Usuario no autorizado', '', 401); die(); }
		}
		if(REST::getData() == 'cliente') // Access with token from config
		{
			$alloweds = array('Producto.get', 'Reserva.post');
			if(!in_array($class . '.' . $method, $alloweds)) { \Error\Error::showMessage('Usuario no autorizado', '', 401); die(); }
		}
		/* END REST */
	}
}
