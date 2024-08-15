<?php
namespace core\rest;

require_once(PATH_CORE . '/middleware/Middleware.php');

use core\middleware\Middleware;

class RESTMiddleware extends Middleware
{
    private $_rest;

    public function __construct(Rest $rest = NULL)
    {
        if($rest == NULL) { $this->_rest = Rest::getInstance(); }
        else { $this->_rest = $rest; }
    }

    public function execute()
    {
        assert($this->getURIDecoder() != NULL, 'The URI decoder is not set');

        $class = $this->getURIDecoder()->getClass();

        if($this->_rest->auth($class))
        {
            $data = $this->_rest->getData();

            $method = $this->getURIDecoder()->getMethod();
			if($data == 'usuario-login') // Access with special token
			{
				$is_usuario_login = $class == 'Usuario' && $method == 'get' && isset($_GET['user']) && isset($_GET['password']);
				if(!$is_usuario_login) { return false; }
			}
			if($data == 'another-token') // Another example with special token
			{
				$alloweds = array('Persona.get', 'Extras.post', 'Persona.put', 'Persona.post', 'Tramite.post', 'Tramite.get', 'Oficina.get');
				if(!in_array($class . '.' . $method, $alloweds)) { return false; }
			}

            return true;
        }

        return false;
    }
}