<?php
// DB
spl_autoload_register(function($class_name){
    if($class_name == 'DB\DB')
    {
        $fullpath = LIBRARIES . DIRECTORY_SEPARATOR . 'DB' . DIRECTORY_SEPARATOR . 'DB.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// Model
spl_autoload_register(function($class_name){
    $fullpath = MODEL . DIRECTORY_SEPARATOR . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});

// InterfaceDAO
spl_autoload_register(function($interface_name){
    if(substr($interface_name, -3) == 'DAO')
    {
        $fullpath = MODEL . strtolower(substr($interface_name, 1, -3)) . 'DAO' . DIRECTORY_SEPARATOR . $interface_name . '.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// DAO
spl_autoload_register(function($class_name){
    if(substr($class_name, -5) == 'MYSQL')
    {
        $fullpath = MODEL . strtolower(substr($class_name, 0, -5)) . 'DAO' . DIRECTORY_SEPARATOR . $class_name . '.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// Class Validator
spl_autoload_register(function($class_name){
    if($class_name == 'Validate\Validator')
    {
        $fullpath = LIBRARIES . DIRECTORY_SEPARATOR . 'Validate' . DIRECTORY_SEPARATOR . 'Validator.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// Validator
spl_autoload_register(function($class_name){
    if(substr($class_name, -9) == 'Validator')
    {
        $fullpath = VALIDATOR . DIRECTORY_SEPARATOR . $class_name . '.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// Helper
spl_autoload_register(function($class_name){
    if(substr($class_name, -6) == 'Helper')
    {
        $fullpath = HELPER . DIRECTORY_SEPARATOR . $class_name . '.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// Class Render
spl_autoload_register(function($class_name){
    if($class_name == 'Render\Render')
    {
        $fullpath = LIBRARIES . DIRECTORY_SEPARATOR . 'Render' . DIRECTORY_SEPARATOR . 'Render.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});

// View
spl_autoload_register(function($class_name){
    if(substr($class_name, -4) == 'View')
    {
        $fullpath = VIEW . DIRECTORY_SEPARATOR . $class_name . '.php';
        if(file_exists($fullpath)) { require_once($fullpath); }
    }
});
