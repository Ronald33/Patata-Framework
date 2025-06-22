<?php
$config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini');
define('IN_PRODUCTION', $config['IN_PRODUCTION']);
define('ENABLE_REST', $config['ENABLE_REST']);
define('SHOW_ALL_ERRORS', $config['SHOW_ALL_ERRORS']);
define('TIMEZONE', $config['TIMEZONE']);
define('ENABLE_CUSTOM_ERRORS', $config['ENABLE_CUSTOM_ERRORS']);

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'config.php');
