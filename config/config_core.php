<?php
require_once('core/Helper/Helper.php');
use Helper\Helper;

# Constantes
// Este valor indica el sufijo de un controlador, ejemplo: Page = PageController
define('CONTROLLER_SUFFIX', 'Controller');

// Valor que indica el nombre del controlador que debe ejecutarse en caso de que ocurra un error
define('ERROR_CONTROLLER', 'Page');
// Valor que indica el nombre del metodo que debe ejecutarse en caso de que ocurra un error
define('ERROR_METHOD', 'showError');

// Valor que indica el nombre del controlador que debe ejecutarse en caso de que se haga una peticion a una URL inexistente
define('S404_CONTROLLER', 'Page');
// Valor que indica el nombre del metodo que debe ejecutarse en caso de que se haga una peticion a una URL inexistente
define('S404_METHOD', 'S404');

# Routes
// Rutas habitualmente usadas para el desarrollo de cualquier proyecto
define('BACK_END', 'back-end/');
define('MODEL', BACK_END . 'model/');
define('VIEW', BACK_END . 'view/');
define('CONTROLLER', BACK_END . 'controller/');
define('HELPER', BACK_END . 'helper/');
define('LIBRARIES', BACK_END . 'libraries/');
define('HTML', 'front-end/html/'); // Access to html files

define('URL_BASE', Helper::getURLBase());
define('FRONT_END', URL_BASE . 'front-end/');
define('CSS', FRONT_END . 'css/');
define('JS', FRONT_END . 'js/');
define('VENDOR', FRONT_END . 'vendor/');
