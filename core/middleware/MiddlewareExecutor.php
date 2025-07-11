<?php
namespace core\middleware;

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'MiddlewareRestOptions.php');

class MiddlewareExecutor
{
	private $_middlewaresClassics;
	private $_middlewaresRests;

	public function __construct()
	{
		$this->_middlewaresClassics = [];
		$this->_middlewaresRests = [];
		array_push($this->_middlewaresRests, new MiddlewareRestOptions());
	}

	public function addMiddlewareClassic(Middleware $_middlewareClassic) { array_push($this->_middlewaresClassics, $_middlewareClassic); }
	public function addMiddlewareRest(Middleware $_middlewareRest) { array_push($this->_middlewaresRests, $_middlewareRest); }
	public function execute()
	{
		if(!ENABLE_REST || \Repository::getREST()->getData() == 'CLASS-EXCEPTIONS')
		{
			foreach($this->_middlewaresClassics as $middleware)
			{
				if($middleware->execute() === false) { return false; }
			}
		}
		else
		{
			foreach($this->_middlewaresRests as $middleware)
			{
				if($middleware->execute() === false) { return false; }
			}
		}

		return true;
	}
}