<?php
// Model
spl_autoload_register(function($class_name){
    $fullpath = PATH_MODEL . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});

// Helper
spl_autoload_register(function($class_name){
    $fullpath = PATH_HELPER . DIRECTORY_SEPARATOR . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});

// DAO
spl_autoload_register(function($class_name){
    $fullpath = PATH_MODEL . DIRECTORY_SEPARATOR . 'dao' . DIRECTORY_SEPARATOR . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});

// View
spl_autoload_register(function($class_name){
    $fullpath = PATH_VIEW . DIRECTORY_SEPARATOR . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'autoload.php');