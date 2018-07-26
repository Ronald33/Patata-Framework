<?php
namespace Render;
abstract class Message
{
	public static $default = 'Plantilla no encontrada';
	public static function noFile($file) { return 'El archivo: ' . $file . ' no existe'; }
}