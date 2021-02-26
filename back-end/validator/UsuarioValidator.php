<?php
abstract class UsuarioValidator
{
    public static function validate($data)
    {
        $validator = Repository::getValidator();
        $validator->addInputFromObject('Tipo', $data, 'tipo')->addRule('isIn', ['ADMINISTRADOR', 'VENDEDOR']);
        $validator->addInputFromObject('Usuario', $data, 'usuario')->addRule('isAlphaNumeric')->addRule('lengthIsGreaterThan', 2)->addRule('lengthIsLessThan', 64)->addRule('isUnique', 'usuarios', 'usua_usuario', isset($data->id) ? 'usua_id != ' . $data->id : '1');
        if(!isset($data->id) || (isset($data->id) && isset($data->cambiarContrasenha) && $data->cambiarContrasenha))
        {
            $validator->addInputFromObject('Contraseña', $data, 'contrasenha')->addRule('lengthIsGreaterThan', 5)->addRule('lengthIsLessThan', 64);
        }
        $validator->addInputFromObject('Persona', $data, 'persona')->addRule('isObject');
        if($validator->isValid()) { return true; }
        else { return $validator->getInputsWithErrors(); }
    }
}
