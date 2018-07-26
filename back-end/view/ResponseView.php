<?php
require_once(HELPER . 'Helper.php');

class ResponseView
{
	// Ok
	public function s200($message = '') { Helper::respondWithJSON($message, 200); }
	// Nuevo elemento creado (usualmente usado despues de PUT)
	public function s201($message = '') { Helper::respondWithJSON($message, 201); }
	// Solicitud erronea
	public function s400($message = '') { Helper::respondWithJSON($message, 400); }
	// No se tiene la autorizacion
	public function s401($message = '') { Helper::respondWithJSON($message, 401); }
	// Pago requerido
	public function s402($message = '') { Helper::respondWithJSON($message, 402); }
	// No se tiene los suficientes privilegios
	public function s403($message = '') { Helper::respondWithJSON($message, 403); }
	// Recurso no encontrado
	public function s404($message = '') { Helper::respondWithJSON($message, 404); }
	// Error en el servidor
	public function s500($message = '') { Helper::respondWithJSON($message, 500); }
	// Metodo no implementado
	public function s501($message = '') { Helper::respondWithJSON($message, 501); }
}