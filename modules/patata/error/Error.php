<?php
namespace modules\patata\error;

require_once(PATH_BASE . '/core/IError.php');

use \core\IError;

class Error implements IError
{
	private $config;
	private $show_errors;
	private static $path_config = __DIR__ . '/config.ini';
	
	public function __construct($show_errors = NULL)
	{
		$this->config = parse_ini_file(self::$path_config);
		if($show_errors === NULL) { $this->show_errors = $this->config['SHOW_ERRORS']; }
	}
	
	public function showMessage($messageDevelopment, $messageProduction, $code = 500)
	{
		http_response_code($code);
		if($this->show_errors) { throw new \Exception($messageDevelopment); }
		else { echo json_encode($messageProduction); die(); }
	}
}
