<?php
// Variable utilizada por las librerias, la cual indica si se debe imprimir o no los errores en pantalla
define('IS_PRODUCTION', false);
// Clase por defecto, esto significa que se cargara "PageController" como controlador inicial
define('CLASS_DEFAULT', 'Page');
// Este atributo representa que metodo debe ejecutar en caso de que no se especifique un controlador
define('METHOD_DEFAULT', 'index');
// Variables de meta informacion (Usadas en la Libreria Render)
define('TITLE', 'PF');
define('DESCRIPTION', 'Hecho con PF :)');
// REST
define('ENABLE_REST', true);
