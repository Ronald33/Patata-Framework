<?php
require_once(LIBRARIES . 'Validate/Validator.php');

use Validate\Validator;

abstract class EditorialValidator
{
    public static function tryValidate($data)
    {
        $validator = new Validator();
        $validator->addValue('Nombre', isset($data->nombre) ? $data->nombre : NULL, false)->addRule('isWords')->addRule('isLess', 145);
        return self::validateForm($validator);
    }

    private static function validateForm($validator)
    {
        if($validator->isValid()) { return true; }
        else { return $validator->getInputsWithErrors(); }
    }
}