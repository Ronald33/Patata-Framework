<?php
abstract class Helper
{
	public static function respondWithJSON($message, $code)
	{
		http_response_code($code);
		header('Content-Type: application/json');
        echo json_encode($message);
	}

	/*public static function myFunction()
	{
		
	}*/
}