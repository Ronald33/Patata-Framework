<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'PatataHelper.php');

define('CUSTOM_CONFIG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'config.ini');

define('PATH_ROOT', __DIR__);

// For custom
define('PATH_FOR_CUSTOM', PATH_ROOT . DIRECTORY_SEPARATOR . 'for-custom');

// For back end
define('PATH_CORE', PATH_ROOT . DIRECTORY_SEPARATOR . 'core');
define('PATH_MODULES', PATH_ROOT . DIRECTORY_SEPARATOR . 'modules');
define('PATH_MODULES_PATATA', PATH_MODULES . DIRECTORY_SEPARATOR . 'patata');

define('PATH_BACK_END', PATH_ROOT . DIRECTORY_SEPARATOR . 'back-end');
define('PATH_CONTROLLER', PATH_BACK_END . DIRECTORY_SEPARATOR . 'controller');
define('PATH_MODEL', PATH_BACK_END . DIRECTORY_SEPARATOR . 'model');
define('PATH_VIEW', PATH_BACK_END . DIRECTORY_SEPARATOR . 'view');
define('PATH_VALIDATOR', PATH_BACK_END . DIRECTORY_SEPARATOR . 'validator');
define('PATH_HELPER', PATH_BACK_END . DIRECTORY_SEPARATOR . 'helper');

define('PATH_FRONT_END', PATH_ROOT . DIRECTORY_SEPARATOR . 'front-end');
define('PATH_HTML', PATH_FRONT_END . DIRECTORY_SEPARATOR . 'html');

define('PATH_RESOURCES_PUBLIC', PATH_ROOT . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'public');
define('PATH_RESOURCES_PRIVATE', PATH_ROOT . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'private');
define('PATH_GENERATEDS', PATH_RESOURCES_PRIVATE . DIRECTORY_SEPARATOR . 'generateds');
define('PATH_TMP', PATH_RESOURCES_PRIVATE . DIRECTORY_SEPARATOR . 'tmp');

// For front end

define('URL_BASE', 	PatataHelper::getURLBase());
define('PATH_CSS',  URL_BASE . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'css');
define('PATH_JS',   URL_BASE . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'js');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'constants.php');
