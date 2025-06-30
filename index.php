<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'load-config.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'configurator.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'autoload.php');

$composer_autoload = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if(file_exists($composer_autoload)) { require_once($composer_autoload); }

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'Repository.php');

$uriDecoder = Repository::getURIDecoder();
if(ENABLE_REST) { Repository::getREST()->auth($uriDecoder->getClass()); }
$middlewareExecutor = Repository::getMiddlewareExecutor();
if($middlewareExecutor->execute() === false) { Repository::getResponse()->j401(); }
Repository::getCaller()->execute($uriDecoder->getClass(), $uriDecoder->getMethod(), $uriDecoder->getArguments());