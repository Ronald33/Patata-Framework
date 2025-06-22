<?php
require_once(PATH_BASE . DIRECTORY_SEPARATOR . 'PatataRepository.php');
abstract class Repository extends PatataRepository
{
    // Here you can add custom methods that instantiate objects
    public static function getMyMiddleware()
    {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . 'MyMiddleware.php');
        $middleware = new MyMiddleware();
        $middleware->setURIDecoder(self::getURIDecoder());
        return $middleware;
    }
}