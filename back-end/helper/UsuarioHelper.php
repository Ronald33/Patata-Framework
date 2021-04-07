<?php
abstract class UsuarioHelper
{
    public static function castToUsuario($object)
    {
        $response = Repository::getResponse();
        $usuario = NULL;
        $result = UsuarioValidator::validate($object);
        
        if($result === true)
        {
            switch($object->tipo)
            {
                case 'ADMINISTRADOR':
                    $usuario = Helper::cast('Administrador', $object);
                break;
                case 'VENDEDOR':
                    $usuario = Helper::cast('Vendedor', $object);
                break;
            }

            $persona = NULL;
            if(isset($object->persona->id)) { $persona = Helper::cast('Persona', $object->persona); }
            else { $persona = PersonaHelper::castToPersona($object->persona); }
            $usuario->setPersona($persona);
            
            // Asignamos los valores por defecto
            $usuario->setHabilitado(true);

            if(!isset($object->id) || (isset($object->id) && isset($object->cambiarContrasenha) && $object->cambiarContrasenha))
            {
                $usuario->setContrasenha($object->contrasenha);
            }

            return $usuario;
        }
        else { $response->s400($result); }
    }
}