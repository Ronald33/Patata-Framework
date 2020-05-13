<?php
namespace Modules\Patata\Render;

abstract class Message
{
	public static function notFound() { return 'Plantilla no encontrada'; }
	public static function noFile($file) { return 'El archivo: ' . $file . ' no existe'; }
}
