<?php
require_once(LIBRARIES . 'Validate/Validator.php');

use Validate\Validator;

abstract class EditorialValidator
{
    public static function tryValidate($data)
    {
        if(isset($data->nombre)) { $nombre = $data->nombre; } else { $nombre = NULL; }
        $validator = new Validator();
        $validator->addValue('Nombre', $nombre, true)->addRule('isWords')->addRule('isLess', 145);
        return self::validateForm($validator);
    }

    private static function validateForm($validator)
    {
        if($validator->isValid()) { return true; }
        else { return $validator->getInputsWithErrors(); }
    }
}