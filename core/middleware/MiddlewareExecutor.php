<?php
namespace core\middleware;

class MiddlewareExecutor
{
	private static $instance = null;

	private $_middlewares;

	private function __construct() { $this->_middlewares = []; }

	public static function getInstance()
	{
		if(self::$instance == null) { self::$instance = new MiddlewareExecutor(); }
		return self::$instance;
	}

	public function add(Middleware $middleware) { array_push($this->_middlewares, $middleware); }
	public function execute()
	{
		foreach($this->_middlewares as $middleware)
		{
			if($middleware->execute() === false) { return false; }
		}

		return true;
	}

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}