<?php
namespace Core\Response;

class Response
{
	private static $instance;

	private function __construct() {  }

	public static function getInstance()
	{
		if(self::$instance == NULL) { self::$instance = new Response(); }
		return self::$instance;
	}

	// Ok
	public function s200($message = '') { self::respondWithJSON($message, 200); }
	// Nuevo elemento creado (usualmente usado despues de PUT)
	public function s201($message = '') { self::respondWithJSON($message, 201); }
	// Respuesta a un patch
	public function s204() { self::respondWithJSON('', 204); }
	// Solicitud erronea
	public function s400($message = '') { self::respondWithJSON($message, 400); }
	// No se tiene la autorizacion
	public function s401($message = '') { self::respondWithJSON($message, 401); }
	// No se tiene los suficientes privilegios
	public function s403($message = '') { self::respondWithJSON($message, 403); }
	// Recurso no encontrado
	public function s404($message = '') { self::respondWithJSON($message, 404); }
	// Conflicto en la peticion
	public function s409($message = '') { self::respondWithJSON($message, 409); }
	// Error en el servidor
	public function s500($message = '') { self::respondWithJSON($message, 500); }
	// Metodo no implementado
	public function s501($message = '') { self::respondWithJSON($message, 501); }
	
	public static function respondWithJSON($message, $code)
	{
		http_response_code($code);
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode($message, JSON_NUMERIC_CHECK));
	}

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
