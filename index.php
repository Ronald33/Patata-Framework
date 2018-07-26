<?php
error_reporting(E_ALL); // Borrar luego

require_once('config/config.php');
require_once('config/config_core.php');
require_once('config/my_config.php');
require_once('config/REST/config.php');
require_once('core/URIDecoder/URIDecoder.php');
require_once('core/Caller/Caller.php');
require_once('core/PatataException/PatataException.php');

use UriDecoder\URIDecoder;
use Caller\Caller;
use PatataException\PatataException;

try
{
    $URIDecoder = new URIDecoder();
    $class = $URIDecoder->getClass();
    $method = $URIDecoder->getMethod();
    $arguments = $URIDecoder->getArguments();
    Caller::run($class, $method, $arguments);
}
catch(Exception $e) { PatataException::print($e); }