<?php
namespace Caller;
require_once('Message.php');
require_once('core/Error/Error.php');

use Error\Error;

class Caller
{	
	public static function run($class = CLASS_DEFAULT, $method = METHOD_DEFAULT, $arguments = array())
	{
		$result = self::call($class, $method, $arguments); // Ejecuta la clase y el metodo enviados por parametro
		
		if(is_string($result)) // Si ocurrio un problema en el llamado
		{
			// Control para evitar un bucle infinito en caso no exista el controlador o el encargado de mostrar los errores
			if(($class == ERROR_CONTROLLER && $method == ERROR_METHOD) || ($class == S404_CONTROLLER && $method == S404_METHOD))
			{
				$message = IS_PRODUCTION ? Message::s404() : $result;
				die($message);
			}
			else { Error::show404($result); }
		}
	}
	
    // Funcion encargada de verificar que el controlador pueda ser instanciado, en caso de exito retorna TRUE
    // Caso contrario retorna un string, el cual indica el error a solucionar
	private static function call($class, $method, $arguments)
	{
		$controller = $class . CONTROLLER_SUFFIX;
		$file = CONTROLLER . $controller . '.php';
		
		if(file_exists($file))
		{
			require_once($file);
			if(class_exists($controller))
			{
				$reflectionClass = new \ReflectionClass($controller);
				if($reflectionClass->IsInstantiable())
				{
					$instance = new $controller;
					$data = array($instance, $method);
					if(is_callable($data)) { return call_user_func_array($data, $arguments); }
					else { return Message::noCallable($method); }
				}
				else { return Message::noInstanciable($controller); }
			}
			else { return Message::noClass($controller); }
		}
		else { return Message::noFile($file); }
	}
}
