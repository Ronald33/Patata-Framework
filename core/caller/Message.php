<?php
namespace Core\Caller;

class Message
{
	public static function s404() { return 'Página no encontrada :('; }
	public static function noFile($file) { return 'El archivo: ' . $file . ' no existe'; }
	public static function noClass($class) { return 'La clase: ' . $class . ' no existe'; }
	public static function noInstanciable($class) { return 'No se puede instanciar la clase: ' . $class; }
	public static function noCallable($method) { return 'El método: ' . $method . ' no existe o no es llamable'; }
}
