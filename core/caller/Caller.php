<?php
namespace Core\Caller;
require_once(__DIR__ . '/Message.php');

class Caller
{
	private $_config;
	private $_pathControllers;

	private static $instance;
	
	private function __construct($pathControllers, $extra_configuration_path)
	{
		$this->_pathControllers = $pathControllers;

		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path) : [];
		$this->_config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'), $extra_config);

		$this->checkConfigAsserts();
	}

	private function checkConfigAsserts()
	{
		assert(ctype_alnum($this->_config['CONTROLLER_SUFFIX']), 'In Caller, CONTROLLER_SUFFIX is invalid');
		assert(ctype_alnum($this->_config['S404_CONTROLLER']), 'In Caller, S404_CONTROLLER is invalid');
		assert(ctype_alnum($this->_config['S404_METHOD']), 'In Caller, S404_METHOD is invalid');
	}

	public static function getInstance($pathControllers, $extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new Caller($pathControllers, $extra_configuration_path); }
		return self::$instance;
	}
	
	public function execute($class, $method, $arguments = [])
	{
		$result = $this->call($class, $method, $arguments); // Ejecuta la clase y el metodo enviados por parametro
		
		if(is_string($result)) // Si ocurrio un problema en el llamado
		{
			$class404 = $this->_config['S404_CONTROLLER'];
			$method404 = $this->_config['S404_METHOD'];
			// Control para evitar un bucle infinito en caso no exista el controlador o el encargado de mostrar los errores
			if($class == $class404 && $method == $method404) { die($result); }
			else { $this->execute($class404, $method404); }
		}
	}
	
    // Funcion encargada de verificar que el controlador pueda ser instanciado, en caso de exito retorna TRUE
    // Caso contrario retorna un string, el cual indica el error a solucionar
	private function call($class, $method, $arguments = [])
	{
		$controller = $class . $this->_config['CONTROLLER_SUFFIX'];
		$file = $this->_pathControllers . '/' . $controller . '.php';
		
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
