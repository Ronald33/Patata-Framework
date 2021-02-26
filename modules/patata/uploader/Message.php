<?php
namespace modules\patata\uploader;

abstract class Message
{
    public static $byDefault = 'Ocurrió un error subiendo el archivo';

    public static function overflow($sizeSetted, $maxSize)
    {
        return 'El limite asignado (' . $sizeSetted . ') supera el máximo soportado por la configuración de PHP (' . $maxSize . ')';
    }

    public static function error($key)
    {
        return 'Ocurrió un error subiendo el archivo: ' . $_FILES[$key]['name'];
    }
}