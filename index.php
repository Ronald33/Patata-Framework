<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('constants.php');
require_once('core/patataException/PatataException.php');
require_once('autoload.php');
require_once('Repository.php');

use core\PatataException\PatataException;

try
{
	$config = parse_ini_file('config.ini');
	$uriDecoder = Repository::getURIDecoder();
	$middleware = Repository::getMiddleware();
	$caller = Repository::getCaller(PATH_CONTROLLER);
	if($config['ENABLE_REST'])
	{
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
		$uriDecoder->setREST(Repository::getREST());
	}
	$uriDecoder->execute();
	$middleware->execute($uriDecoder);
	$caller->execute($uriDecoder->getClass(), $uriDecoder->getMethod(), $uriDecoder->getArguments());
}
catch(Exception $e) { PatataException::jprint($e); }
