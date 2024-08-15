<?php
require_once(PATH_CORE . '/middleware/Middleware.php');
require_once(PATH_BASE . DIRECTORY_SEPARATOR . 'PatataHelper.php');

use core\middleware\Middleware;

class MyMiddleware extends Middleware
{
    public function __construct() {  }

    public function execute()
    {
        assert($this->getURIDecoder() != NULL, 'The URI decoder is not set');

        if(ENABLE_REST) { return $this->evaluationForREST(); }
        else { return $this->evaluationForClassical(); }
    }

    private function evaluationForREST()
    {
        $class = $this->getURIDecoder()->getClass();

        if(Repository::getREST()->dataIsDecodable())
        {
            $method = $this->getURIDecoder()->getMethod();
            $arguments = $this->getURIDecoder()->getArguments();
            $payload = PatataHelper::getPayload();
            $user = PatataHelper::getCurrentUser();
            $tipo = $user->tipo;

            if($tipo == 'ADMINISTRADOR')
            {

            }
            else if($tipo == 'VENDEDOR')
            {
                if($class == 'Usuario')
                {
                    if(in_array($method, ['post', 'delete', 'patch'])) { return false; }
                    if($method == 'put')
                    {
                        if($user->id != $arguments[0]) { return false; } // Si intenta editar otro usuario
                        if($user->persona->id != $payload->persona->id) { return false; } // Si intenta modifica la persona asociada
                        if($payload->tipo == 'ADMINISTRADOR') { return false; } // Si se pone el rol de administrador
                    }
                }
            }
        }

        return true;
    }

    private function evaluationForClassical()
    {
        return true;
    }
}