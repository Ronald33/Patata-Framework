<?php
namespace REST;
abstract class Helper
{
	public static function respondWithJSON($message, $code)
	{
		http_response_code($code);
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode($message, JSON_NUMERIC_CHECK));
	}
}
