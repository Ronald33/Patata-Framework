<?php
define('PATH_BASE', './../../..');
require_once(__DIR__ . '/../URIDecoder.php');

use core\uriDecoder\URIDecoder;

/*
 * 
 * Link example: /modules/Patata/URIDecoder/Example/my_class/my_method/arg1/arg2
 * 
 * */

$uri = URIDecoder::getInstance();

echo 'Class: ' . $uri->getClass();
echo '<br>';

echo 'Method: ' . $uri->getMethod();
echo '<br>';

echo 'Arguments: ';
print_r($uri->getArguments());
