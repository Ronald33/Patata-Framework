<?php
class MyRule
{
    public function getMessages()
    {
        return 
        [
            // 'youRule' => 'You message' // The key must be the same as the name of the method it refers to
            'hasTwoDecimals'            => 'Debe de ser un valor con 2 decimales'
        ];
    }

    public static function hasTwoDecimals($value)
    {
        return preg_match('/^\d+(\.\d{1,2})?$/', $value);
    }

    /*
    public static function yourRule()
    {
        return false; // Return a boolean
    }
    */
}
