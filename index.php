<?php
error_reporting();
require_once('config/config.php');
require_once('config/config_core.php');
require_once('config/my_config.php');
require_once('core/Middleware/Middleware.php');
require_once('core/URIDecoder/URIDecoder.php');
require_once('core/Caller/Caller.php');
require_once('core/PatataException/PatataException.php');
if(ENABLE_REST)
{
	require_once('core/REST/REST.php');
	require_once('core/REST/Response/Response.php');
}
use UriDecoder\URIDecoder;
use Caller\Caller;
use PatataException\PatataException;

try
{
	Middleware::executePreURIDecoder();
    $URIDecoder = new URIDecoder();
    $class = $URIDecoder->getClass();
    $method = $URIDecoder->getMethod();
    $arguments = $URIDecoder->getArguments();
	Middleware::executePreCaller();
    Caller::run($class, $method, $arguments);
}
catch(Exception $e) { PatataException::jprint($e); }
