<?php
namespace core\caller;

class Message
{
	public static function noFile($file) { return 'El archivo: ' . $file . ' no existe'; }
	public static function noClass($class) { return 'La clase: ' . $class . ' no existe'; }
	public static function noInstanciable($class) { return 'No se puede instanciar la clase: ' . $class; }
	public static function noCallable($method) { return 'El método: ' . $method . ' no existe o no es llamable'; }
}
