<?php
abstract class PersonaValidator
{
    public static function validate($data, $id = NULL)
    {
        $validator = Repository::getValidator();
        $validator->addInputFromObject('Nombres', $data, 'nombres')->addRule('isWords')->addRule('minLengthIs', 3)->addRule('maxLengthIs', 128);
        $validator->addInputFromObject('Apellidos', $data, 'apellidos')->addRule('isWords')->addRule('minLengthIs', 3)->addRule('maxLengthIs', 128);
        $validator->addInputFromObject('Documento', $data, 'documento')->addRule('maxLengthIs', 16)->addRule('isUnique', 'personas', 'pers_documento', isset($id) ? 'pers_id != ' . $id : '1');
        $validator->addInputFromObject('Email', $data, 'email', true)->addRule('isEmail')->addRule('maxLengthIs', 64);
        $validator->addInputFromObject('Telefono', $data, 'telefono', true)->addRule('minLengthIs', 6)->addRule('maxLengthIs', 16);
        $validator->addInputFromObject('Dirección', $data, 'direccion', true)->addRule('minLengthIs', 6)->addRule('maxLengthIs', 128);
        
        if($validator->hasErrors()) { return $validator->getInputsWithErrors(); }
        return true;
    }
}
