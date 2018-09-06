<?php
require_once(LIBRARIES . 'Validate/Validator.php');

use Validate\Validator;

abstract class LibroValidator
{
    private static $estados = array('DISPONIBLE', 'PRESTADO', 'NO DISPONIBLE');
    public static function tryValidate($data)
    {
        $validator = new Validator();
        $validator->addValue('Titulo', isset($data->titulo) ? $data->titulo : NULL, false)->addRule('isWords')->addRule('isLess', 145);
        $validator->addValue('Descripción', isset($data->descripcion) ? $data->descripcion : NULL, false);
        $validator->addValue('Páginas', isset($data->paginas) ? $data->paginas : NULL, true)->addRule('isInt')->addRule('isPositive');
        $validator->addValue('Estado', isset($data->estado) ? $data->estado : NULL, true)->addRule('isIn', self::$estados);
        $validator->addValue('Fecha de ingreso', isset($data->fecha_ingreso) ? $data->fecha_ingreso : NULL, true)->addRule('isDate');
        $validator->addValue('Autores', isset($data->autores) ? $data->autores : NULL, false)->addRule('hasElements')->addRule('hasUniqueValues');
        return self::validateForm($validator);
    }

    private static function validateForm($validator)
    {
        if($validator->isValid()) { return true; }
        else { return $validator->getInputsWithErrors(); }
    }
}