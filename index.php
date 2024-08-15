<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'load-config.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'configurator.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'Repository.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'autoload.php');

$uriDecoder = Repository::getURIDecoder();
$middlewareExecutor = Repository::getMiddlewareExecutor();
if(ENABLE_REST) { $middlewareExecutor->add(Repository::getRESTMiddleware($uriDecoder)); }
$middlewareExecutor->add(Repository::getMyMiddleware());
if($middlewareExecutor->execute() === false) { Repository::getResponse()->j401(); }
Repository::getCaller()->execute($uriDecoder->getClass(), $uriDecoder->getMethod(), $uriDecoder->getArguments());