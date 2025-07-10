<?php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');

class MyMiddlewareRest implements core\middleware\Middleware
{
    private $uriDecoder;

    public function __construct()
    {
        $this->uriDecoder = Repository::getURIDecoder();
    }

    public function execute()
    {
        if(Repository::getREST()->dataIsDecodable()) { return $this->evaluate(); }
        else { return $this->evaluateSpecialCases(); }
    }

    private function evaluate()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $arguments = $this->uriDecoder->getArguments();

        return true;
    }

    private function evaluateSpecialCases()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $arguments = $this->uriDecoder->getArguments();
        $data = Repository::getREST()->getData();

        if($data == 'CLASS_EXCEPTIONS') { return true; }

        if($data == 'SKIP_AUTH')
        {
            // Verify access to specific resources
            return true;
        }

        return false;
    }
}