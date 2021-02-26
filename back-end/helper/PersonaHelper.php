<?php
abstract class PersonaHelper
{
    public static function castToPersona($object)
    {
        $response = Repository::getResponse();
        $result = PersonaValidator::validate($object);
        
        if($result === true) { $persona = Helper::cast('Persona', $object); }
        else { $response->s400($result); }
        
        return $persona;
    }
}