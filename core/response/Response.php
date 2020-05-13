<?php
namespace Core\Response;

abstract class Response
{
	// Ok
	public static function s200($message = '') { self::respondWithJSON($message, 200); }
	// Nuevo elemento creado (usualmente usado despues de PUT)
	public static function s201($message = '') { self::respondWithJSON($message, 201); }
	// Solicitud erronea
	public static function s400($message = '') { self::respondWithJSON($message, 400); }
	// No se tiene la autorizacion
	public static function s401($message = '') { self::respondWithJSON($message, 401); }
	// No se tiene los suficientes privilegios
	public static function s403($message = '') { self::respondWithJSON($message, 403); }
	// Recurso no encontrado
	public static function s404($message = '') { self::respondWithJSON($message, 404); }
	// Conflicto en la peticion
	public static function s409($message = '') { self::respondWithJSON($message, 409); }
	// Error en el servidor
	public static function s500($message = '') { self::respondWithJSON($message, 500); }
	// Metodo no implementado
	public static function s501($message = '') { self::respondWithJSON($message, 501); }
	
	public static function respondWithJSON($message, $code)
	{
		http_response_code($code);
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode($message, JSON_NUMERIC_CHECK));
	}
}
