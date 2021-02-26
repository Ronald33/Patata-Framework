<?php
require_once('core/helper/Helper.php');
use core\Helper\Helper as PatataHelper;

define('PATH_BASE', __DIR__);
define('PATH_CONTROLLER', PATH_BASE . '/back-end/controller');
define('PATH_MODEL', PATH_BASE . '/back-end/model');
define('PATH_VIEW', PATH_BASE . '/back-end/view');
define('PATH_VALIDATOR', PATH_BASE . '/back-end/validator');
define('PATH_HELPER', PATH_BASE . '/back-end/helper');
define('PATH_HTML', PATH_BASE . '/front-end/html'); 

define('URL_BASE', 	PatataHelper::getURLBase());
define('PATH_CSS', URL_BASE . '/front-end/css');
define('PATH_JS', URL_BASE . '/front-end/js');