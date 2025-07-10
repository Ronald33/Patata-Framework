<?php
namespace core\middleware;

class MiddlewareExecutor
{
	private $_middlewaresClassic;
	private $_middlewaresRest;

	public function __construct() { $this->_middlewaresClassic = []; $this->_middlewaresRest = []; }

	public function addMiddlewareClassic(Middleware $_middlewareClassic) { array_push($this->_middlewaresClassic, $_middlewareClassic); }
	public function addMiddlewareRest(Middleware $_middlewareRest) { array_push($this->_middlewaresRest, $_middlewareRest); }
	public function execute()
	{
		if(!ENABLE_REST || \Repository::getREST()->getData() == 'CLASS-EXCEPTIONS')
		{
			foreach($this->_middlewaresClassic as $middleware)
			{
				if($middleware->execute() === false) { return false; }
			}
		}
		else
		{
			foreach($this->_middlewaresRest as $middleware)
			{
				if($middleware->execute() === false) { return false; }
			}
		}

		return true;
	}
}