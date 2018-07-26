<?php
namespace Error;

require_once('core/Caller/Caller.php');
use Caller\Caller;

abstract class Error
{
	public static function show404($message)
	{
		http_response_code(404);
		if(IS_PRODUCTION) { Caller::run(S404_CONTROLLER, S404_METHOD); }
		else { throw new \Exception($message); }
	}

	public static function showMessage($messageDevelopment, $messageProduction, $code = 500)
	{
		http_response_code($code);
		if(IS_PRODUCTION) { echo json_encode($messageProduction); die(); }
		else { throw new \Exception($messageDevelopment); }
	}
}