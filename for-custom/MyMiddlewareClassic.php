<?php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');

class MyMiddlewareClassic implements core\middleware\Middleware
{
    private $uriDecoder;

    public function __construct()
    {
        $this->uriDecoder = Repository::getURIDecoder();
    }

    public function execute()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $arguments = $this->uriDecoder->getArguments();

        return true;
    }
}