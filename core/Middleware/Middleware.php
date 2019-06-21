<?php

use \REST\REST;

abstract class Middleware
{
	public static function executePreURIDecoder()
	{
		/* REST */
		if(\ENABLE_REST)
		{
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Headers: *');
		}
		/* END REST */
	}
	
	public static function executePreCaller()
	{
		/* REST */
		if(\ENABLE_REST) { REST::auth(); }
		/* END REST */
	}
}
