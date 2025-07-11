<?php
namespace core\middleware;

use Repository;

require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');

class MiddlewareRestOptions implements Middleware
{
    private $uriDecoder;

    public function __construct()
    {
        $this->uriDecoder = \Repository::getURIDecoder();
    }

    public function execute()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $data = \Repository::getREST()->getData();
        $caller = \Repository::getCaller();

        if($data == 'CLASS_EXCEPTIONS') { return true; }
        else
        {
            if($method == 'OPTIONS')
            {
                header('Access-Control-Allow-Methods: ' . \PatataHelper::getAllowedMethodsFromClass($caller->getReflectionClass($class))); die();
            }
        }

        return true;
    }
}