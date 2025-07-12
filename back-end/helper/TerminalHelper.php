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

        return $terminal;
    }

    public static function fillValidator($validator, $data, $id = NULL)
    {
        $validator->addInputFromObject('Nombre', $data, 'nombre')->addRule('minLengthIs', 2)->addRule('maxLengthIs', 64)->addRule('isUnique', 'terminales', 'term_nombre', isset($id) ? 'term_id != ' . $id : '1');
        
        if($id != NULL)
        {
            $validator->addInputFromObject('Habilitado', $data, 'habilitado')->addRule('isBoolean');
        }
    }
}