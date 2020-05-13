<?php
namespace Core\Caller;

class Message
{
	public static function s404() { return 'P&aacute;gina no encontrada :('; }
	public static function noFile($file) { return 'El archivo: ' . $file . ' no existe'; }
	public static function noClass($class) { return 'La clase: ' . $class . ' no existe'; }
	public static function noInstanciable($class) { return 'No se puede instanciar la clase: ' . $class; }
	public static function noCallable($method) { return 'El m&eacute;todo: ' . $method . ' no existe o no es llamable'; }
}
