<?php
// Model
spl_autoload_register(function($class_name){
    $fullpath = PATH_MODEL . DIRECTORY_SEPARATOR . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});

// View
spl_autoload_register(function($class_name){
    if(substr($class_name, -4) == 'View')
    {
        $fullpath = PATH_VIEW . DIRECTORY_SEPARATOR . $class_name . '.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});
