<?php
namespace core\caller;
require_once(__DIR__ . '/Message.php');

class Caller
{
	private $_config;
	private $_pathControllers;

	private static $instance;
	
	private function __construct($path_controllers, $extra_configuration_path)
	{
		$this->_pathControllers = $path_controllers;

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

	public static function getInstance($path_controllers, $extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new Caller($path_controllers, $extra_configuration_path); }
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
		$controller = $class . $this->getSuffix();

		$result = $this->getReflectionClass($class);
		if(is_string($result)) { return $result; }
		else
		{
			$instance = new $controller;

			if($result->hasMethod($method))
			{
				$reflectionMethod = $result->getMethod($method);
				
				$params = $reflectionMethod->getParameters();
				$totalParams = count($params);

				$filledArguments = array_pad($arguments, $totalParams, null);

				return $reflectionMethod->invokeArgs($instance, $filledArguments);
			}
			else { return Message::noCallable($method); }
		}
	}

	public function getReflectionClass($class)
	{
		$controller = $class . $this->getSuffix();
		$file = $this->_pathControllers . '/' . $controller . '.php';
		
		if(file_exists($file))
		{
			require_once($file);
			if(class_exists($controller))
			{
				$reflectionClass = new \ReflectionClass($controller);
				if($reflectionClass->IsInstantiable()) { return $reflectionClass; }
				else { return Message::noInstanciable($controller); }
			}
			else { return Message::noClass($controller); }
		}
		else { return Message::noFile($file); }
	}

	public function getSuffix() { return $this->_config['CONTROLLER_SUFFIX']; }
	public function getPathControllers() { return $this->_pathControllers; }
}
