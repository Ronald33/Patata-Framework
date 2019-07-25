<?php
namespace REST;
require_once('core/REST/Helper/Helper.php');
abstract class Response
{
	// Ok
	public static function s200($message = '') { Helper::respondWithJSON($message, 200); }
	// Nuevo elemento creado (usualmente usado despues de PUT)
	public static function s201($message = '') { Helper::respondWithJSON($message, 201); }
	// Solicitud erronea
	public static function s400($message = '') { Helper::respondWithJSON($message, 400); }
	// No se tiene la autorizacion
	public static function s401($message = '') { Helper::respondWithJSON($message, 401); }
	// No se tiene los suficientes privilegios
	public static function s403($message = '') { Helper::respondWithJSON($message, 403); }
	// Recurso no encontrado
	public static function s404($message = '') { Helper::respondWithJSON($message, 404); }
	// Conflicto en la peticion
	public static function s409($message = '') { Helper::respondWithJSON($message, 409); }
	// Error en el servidor
	public static function s500($message = '') { Helper::respondWithJSON($message, 500); }
	// Metodo no implementado
	public static function s501($message = '') { Helper::respondWithJSON($message, 501); }
}
