<?php
namespace Core\Caller;
require_once(__DIR__ . '/Message.php');

class Caller
{
	private $config;
	private $path;

	private static $instance;
	
	private function __construct($path)
	{
		$this->config = parse_ini_file(__DIR__ . '/config.ini');
		$this->path = $path;
	}

	public static function getInstance($path = '.')
	{
		if(self::$instance == NULL) { self::$instance = new Caller($path); }
		return self::$instance;
	}
	
	public function execute($class, $method, $arguments)
	{
		$result = self::call($class, $method, $arguments); // Ejecuta la clase y el metodo enviados por parametro
		
		if(is_string($result)) // Si ocurrio un problema en el llamado
		{
			// Control para evitar un bucle infinito en caso no exista el controlador o el encargado de mostrar los errores
			if($class == $this->config['S404_CONTROLLER'] && $method == $this->config['S404_METHOD']) { die($result); }
			else { self::s404($result); }
		}
	}
	
    // Funcion encargada de verificar que el controlador pueda ser instanciado, en caso de exito retorna TRUE
    // Caso contrario retorna un string, el cual indica el error a solucionar
	private function call($class, $method, $arguments)
	{
		$controller = $class . $this->config['CONTROLLER_SUFFIX'];
		//$file = $this->config['CONTROLLER_PATH'] . '/' . $controller . '.php';
		$file = $this->path . '/' . $controller . '.php';
		
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
	
	public function s404($result)
	{
		$controller = $this->config['S404_CONTROLLER'] . $this->config['CONTROLLER_SUFFIX'];
		$file = $this->path . '/' . $controller . '.php';
		require_once($file);
		$instance = new $controller;
		$data = array($instance, $this->config['S404_METHOD']);
		call_user_func_array($data, [$result]);
	}
}
