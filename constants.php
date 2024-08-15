<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'PatataHelper.php');

define('PATH_BASE', __DIR__);

// For back end
define('PATH_CORE',         PATH_BASE . DIRECTORY_SEPARATOR . 'core');
define('PATH_MODULES',      PATH_BASE . DIRECTORY_SEPARATOR . 'modules');

define('PATH_BACK_END',     PATH_BASE . DIRECTORY_SEPARATOR . 'back-end');
define('PATH_CONTROLLER',   PATH_BACK_END . DIRECTORY_SEPARATOR . 'controller');
define('PATH_MODEL',        PATH_BACK_END . DIRECTORY_SEPARATOR . 'model');
define('PATH_VIEW',         PATH_BACK_END . DIRECTORY_SEPARATOR . 'view');
define('PATH_VALIDATOR',    PATH_BACK_END . DIRECTORY_SEPARATOR . 'validator');
define('PATH_HELPER',       PATH_BACK_END . DIRECTORY_SEPARATOR . 'helper');

define('PATH_FRONT_END',    PATH_BASE . DIRECTORY_SEPARATOR . 'front-end');
define('PATH_HTML',         PATH_FRONT_END . DIRECTORY_SEPARATOR . 'html'); 

// For front end

define('URL_BASE', 	PatataHelper::getURLBase());
define('PATH_CSS',  URL_BASE . DIRECTORY_SEPARATOR . 'front-end ' . DIRECTORY_SEPARATOR . 'css');
define('PATH_JS',   URL_BASE . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'js');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'contants.php');