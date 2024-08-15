<?php
abstract class UsuarioValidator
{
    public static function validate($data, $id = NULL)
    {
        $dao = new ExtrasDAO();
        $tipos = $dao->getEnumValues('usuarios', 'usua_tipo');

        $validator = Repository::getValidator();
        $validator->addInputFromObject('Tipo', $data, 'tipo', true)->addRule('isIn', $tipos);
        $validator->addInputFromObject('Usuario', $data, 'usuario', true)->addRule('isAlphaNumeric')->addRule('minLengthIs', 3)->addRule('maxLengthIs', 16)->addRule('isUnique', 'usuarios', 'usua_usuario', isset($id) ? 'usua_id != ' . $id : '1');
        $validator->addInputFromObject('Contraseña', $data, 'contrasenha', $id == NULL ? false : true)->addRule('minLengthIs', 3)->addRule('maxLengthIs', 16);
        $persona_id = (isset($data->persona) && isset($data->persona->id)) ? $data->persona->id : NULL;
        $validator->addInput('Persona', $persona_id, true)->addRule(['rowExists', 'Ingrese una persona válida'], 'personas', 'pers_id');

        if($validator->hasErrors()) { return $validator->getInputsWithErrors(); }
        return true;
    }
}
