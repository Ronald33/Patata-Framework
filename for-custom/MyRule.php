<?php
class MyRule
{
    public function getMessages()
    {
        return 
        [
            // 'youRule' => 'You message' // The key must be the same as the name of the method it refers to
            'isUnique' =>                       'El valor ingresado ya se encuentra registrado', 
        ];
    }

    /*
    public static function yourRule()
    {
        return false; // Return a boolean
    }
    */

    public static function isUnique($value, $table, $column, $condition = '1')
    {
        $extras_dao = new \ExtrasDAO();
        return $extras_dao->isUnique($value, $table, $column, $condition ?? '1'); // Bug?
    }
}
