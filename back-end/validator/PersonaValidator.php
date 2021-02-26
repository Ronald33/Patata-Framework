<?php
abstract class PersonaValidator
{
    public static function validate($data)
    {
        $validator = Repository::getValidator();
        $validator->addInputFromObject('Nombres', $data, 'nombres')->addRule('isWords')->addRule('lengthIsLessThan', 128);
        $validator->addInputFromObject('Apellidos', $data, 'apellidos')->addRule('isWords')->addRule('lengthIsLessThan', 128);
        $validator->addInputFromObject('Documento', $data, 'documento', false)->addRule('lengthIsLessThan', 16)->addRule('isUnique', 'personas', 'pers_documento', isset($data->id) ? 'pers_id != ' . $data->id : '1');
        $validator->addInputFromObject('Email', $data, 'email', false)->addRule('isEmail')->addRule('lengthIsLessThan', 64);
        $validator->addInputFromObject('Telefono', $data, 'telefono', false)->addRule('lengthIsLessThan', 16);
        $validator->addInputFromObject('Dirección', $data, 'direccion', false)->addRule('lengthIsLessThan', 128);
        
        if($validator->isValid()) { return true; }
        else { return $validator->getInputsWithErrors(); }
    }
}
