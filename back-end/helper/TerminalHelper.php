<?php
abstract class TerminalHelper
{
    public static function castToTerminal($object, $id = NULL)
    {
        $terminal = Helper::cast('Terminal', $object);

        if($id === NULL)
        {
            $terminal->setHabilitado(true);
        }
        else
        {

        }

        return $terminal;
    }

    public static function fillValidator($validator, $data, $id = NULL)
    {
        $validator->addInputFromObject('Nombre', $data, 'nombre')->addRule('isAlphanumericAndSpaces')->addRule('minLengthIs', 2)->addRule('maxLengthIs', 16)->addRule('isUnique', 'terminales', 'term_nombre', isset($id) ? 'term_id != ' . $id : '1');

        if($id != NULL) // For edit cases
        {

        }
    }
}
