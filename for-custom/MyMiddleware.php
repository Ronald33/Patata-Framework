<?php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');
require_once(PATH_BASE . DIRECTORY_SEPARATOR . 'PatataHelper.php');

use core\middleware\Middleware;

class MyMiddleware extends Middleware
{
    public function __construct() {  }

    public function execute()
    {
        assert($this->getURIDecoder() != NULL, 'The URI decoder is not set');

        if(ENABLE_REST)
        {
            if(Repository::getREST()->dataIsDecodable()) { return $this->evaluateWithToken(); }
            else { return $this->evaluationBySpecialCases(); }
        }
        else { return $this->evaluationForClassical(); }
    }

    private function evaluateWithToken()
    {
        $user = PatataHelper::getCurrentUser();
        $class = $this->getURIDecoder()->getClass();
        $method = $this->getURIDecoder()->getMethod();
        $arguments = $this->getURIDecoder()->getArguments();
        $payload = PatataHelper::getPayload();

        return false;
    }

    private function evaluationBySpecialCases()
    {
        $class = $this->getURIDecoder()->getClass();
        $method = $this->getURIDecoder()->getMethod();
        $arguments = $this->getURIDecoder()->getArguments();
        $data = Repository::getREST()->getData();

        if($data == 'CLASS-EXCEPTIONS') { return true; }

        if($data == 'SKIP-AUTH')
        {
            return true;
        }

        return false;
    }

    private function evaluationForClassical()
    {
        return true;
    }
}