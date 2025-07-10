<?php
namespace core\response;

use PatataHelper;

class Response
{
	private $_config;
	private static $instance;

	private function __construct($extra_configuration_path)
	{
		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path) : [];
		$this->_config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'), $extra_config);
	}

	public static function getInstance($extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new Response($extra_configuration_path); }
		return self::$instance;
	}

	public function respondWithText($code, $message = '')
	{
		http_response_code($code);
		header('Content-Type: text/plain; charset=' . $this->_config['RESPONSE_CHARSET']);
		die($message);
	}

	public function respondWithJSON($code, $message = '', $apply_numeric_check = false)
	{
		http_response_code($code);
		header('Content-Type: application/json; charset=' . $this->_config['RESPONSE_CHARSET']);
		die(json_encode($message, ($apply_numeric_check ? JSON_NUMERIC_CHECK : 0) | JSON_UNESCAPED_UNICODE));
	}

	// Ok
	public function j200($message = '', $apply_numeric_check = false) { $this->respondWithJSON(200, $message, $apply_numeric_check); }

	// Nuevo elemento creado
	public function j201($message = '', $apply_numeric_check = false) { $this->respondWithJSON(201, $message, $apply_numeric_check); }

	// La petición se cumplió satisfactoriamente, no hay contenido para devolver (usualmente usado despues de PUT)
	public function j204() { $this->respondWithJSON(204); }
	
	// Solicitud erronea
	public function j400($message = '', $apply_numeric_check = false) { $this->respondWithJSON(400, $message, $apply_numeric_check); }

	// No se tiene la autorizacion
	public function j401($message = '') { $this->respondWithJSON(401, $message); }

	// Acceso denegado
	public function j403($message = '') { $this->respondWithJSON(403, $message); }

	// Recurso no encontrado
	public function j404($message = '') { $this->respondWithJSON(404, $message); }

	// Conflicto
	public function j409($message = '') { $this->respondWithJSON(409, $message); }

	// Recurso bloqueado
	public function j423($message = '') { $this->respondWithJSON(423, $message); }

	// Error en el servidor
	public function j500($message = '') { self::respondWithJSON(500, $message); }

	// Metodo no implementado
	public function j501($message = '') { self::respondWithJSON(501, $message); }

	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
