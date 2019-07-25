<?php
error_reporting(E_ALL);
require_once('config/config.php');
require_once('config/config_core.php');
require_once('config/my_config.php');
require_once('core/Middleware/Middleware.php');
require_once('core/URIDecoder/URIDecoder.php');
require_once('core/Caller/Caller.php');
require_once('core/PatataException/PatataException.php');
require_once('autoloaders.php');
use UriDecoder\URIDecoder;
use Caller\Caller;
use PatataException\PatataException;

try
{
	Middleware::executePreURIDecoder();
    $URIDecoder = new URIDecoder();
	Middleware::executePreCaller($URIDecoder);
    Caller::run($URIDecoder->getClass(), $URIDecoder->getMethod(), $URIDecoder->getArguments());
}
catch(Exception $e) { PatataException::jprint($e); }
