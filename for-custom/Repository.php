<?php
require_once(PATH_ROOT . DIRECTORY_SEPARATOR . 'PatataRepository.php');
abstract class Repository extends PatataRepository
{
    // Here you can add custom methods that instantiate objects
    // public static function getMiddlewareExecutor()
    // {
    //      $middlewareExecutor = parent::getMiddlewareExecutor();
    //      $middlewareExecutor->addMiddlewareRest(new AnotherMiddlewareRest());
    //      return $middlewareExecutor;
    // }
}