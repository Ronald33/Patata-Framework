<?php
namespace core\middleware;

require_once(PATH_BASE . '/core/uriDecoder/URIDecoder.php');
require_once(PATH_BASE . '/core/IError.php');

use core\uriDecoder\uriDecoder;
use core\IError;

class Middleware
{
	private $error;
	
	private static $instance;

	private function __construct()
	{

	}
	
	public function setError(IError $error) { $this->error = $error; }

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
				if(!$is_usuario_login) { $this->error->showMessage('Usuario no autorizado', '', 401); }
			}
			if($rest->getData() == 'sistrami-anonimo') // Access with token from config
			{
				// Quiza se deba usar un switch
				$alloweds = array('Persona.get', 'Extras.post', 'Persona.put', 'Persona.post', 'Tramite.post', 'Tramite.get', 'Oficina.get');
				// if(!in_array($class . '.' . $method, $alloweds) && ($class == 'Tramite' && $method == 'get' && empty($_GET['registro']) && empty($_GET['remitente'])))
				if(!in_array($class . '.' . $method, $alloweds))
				{
					$this->error->showMessage('Usuario no autorizado', '', 401);
				}
			}
			/* End REST exceptions */
		}
		/* Auth REST */
	}

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
