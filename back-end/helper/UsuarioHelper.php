<?php
abstract class UsuarioHelper
{
    public static function castToUsuario($object)
    {
        $usuario = Helper::cast($object->tipo == 'ADMINISTRADOR' ? 'Administrador' : 'Vendedor', $object);
        $usuario->setPersona(Helper::cast('Persona', $object->persona));

        // Asignamos los valores por defecto
        if($usuario->getId() == NULL) { $usuario->setHabilitado(true); }

        return $usuario;
    }
}