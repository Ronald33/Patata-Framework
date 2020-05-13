<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('constants.php');
require_once('core/patataException/PatataException.php');
require_once('autoload.php');
require_once('Repository.php');

use core\Caller\Caller;
use core\PatataException\PatataException;

$config = parse_ini_file('config.ini');

try
{
	$uriDecoder = Repository::getURIDecoder();
	$middleware = Repository::getMiddleware();
	$caller = Repository::getCaller(PATH_CONTROLLER);
	if($config['ENABLE_REST']) { $rest = Repository::getREST(); $uriDecoder->setREST($rest); }
	$uriDecoder->execute();
	$middleware->execute($uriDecoder);
	$caller->execute($uriDecoder->getClass(), $uriDecoder->getMethod(), $uriDecoder->getArguments());
}
catch(Exception $e) { PatataException::jprint($e); }
