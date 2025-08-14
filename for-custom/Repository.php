<?php
require_once(PATH_ROOT . DIRECTORY_SEPARATOR . 'PatataRepository.php');
abstract class Repository extends PatataRepository
{
    // Require: composer require firebase/php-jwt
    // public static function getREST($extra_configuration_path = CUSTOM_CONFIG_PATH)
    // {
    //     require_once(__DIR__ . DIRECTORY_SEPARATOR . 'MyJWT.php');
    //     $rest = parent::getREST($extra_configuration_path);
    //     $rest->setToken(new MyJWT($extra_configuration_path));
    //     return $rest;
    // }
}
