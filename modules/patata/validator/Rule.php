<?php
namespace modules\patata\validator;

class Rule
{
    private $_messages;

    public function __construct()
    {
        $this->_messages = [
            'default' 					=> 'No es válido', 
            'hasContent' 				=> 'No puede estar vacío', 
            'minLengthIs' 				=> 'No cumple con la cantidad mínima de caracteres', 
            'maxLengthIs' 				=> 'Excede la cantidad de caracteres permitidos', 
            'isWord' 					=> 'Solo esta permitido letras', 
            'isWords' 					=> 'Solo esta permitido letras y espacios', 
            'isAlphaNumeric'			=> 'Solo esta permitido cáracteres alfanuméricos', 
            'isAlphaNumericAndSpaces'	=> 'Solo esta permitido cáracteres alfanuméricos y espacios', 
            'isDNI'						=> 'Debe de ser un DNI', 
            'isRUC'						=> 'Debe de ser un RUC', 
            'isEmail' 					=> 'Debe de ser un email', 
            'isUrl' 					=> 'Debe de ser una URL', 
            'isInt' 					=> 'Debe de ser un entero', 
            'isFloat' 					=> 'Debe de ser un número flotante válido', 
            'isPositive' 				=> 'Debe de ser un valor mayor a cero', 
            'isBetween' 				=> 'Esta fuera de rango', 
            'hasElements'				=> 'No se le asigno elementos', 
            'hasUniqueValues'			=> 'Tiene valores duplicados', 
            'isDate' 					=> 'Debe de ser una fecha', 
            'isDateTime'				=> 'Debe de ser una fecha y hora', 
            'isTimestamp'				=> 'Debe de ser un timestamp válido', 
            'isDate' 					=> 'Debe de ser una fecha', 
            'isDifferentTo'				=> 'El valor ingresado no está permitido', 
            'isIn' 						=> 'No se encuentra en las opciones disponibles', 
            'isUnique' 					=> 'El valor ingresado ya se encuentra registrado'
        ];
    }

    public function getMessages() { return $this->_messages; }

    /* Strings */
    public static function isInputText($value) { return is_string($value) || is_numeric($value); }
    public static function isString($value) { return is_string($value); }
    public static function hasContent($value)
    {
        if(!self::isInputText($value)) { return false; }
        return strlen(trim((string) $value)) > 0;
    }
    public static function minLengthIs($value, $size)
    {
        if(!self::isInputText($value)) { return false; } 
        return strlen((string) $value) >= $size;
    }
    public static function maxLengthIs($value, $size)
    {
        if(!self::isInputText($value)) { return false; }
        return strlen((string) $value) <= $size;
    }
    public static function isWord($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/');
    }
    public static function isWords($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/');
    }
    public static function isAlphaNumeric($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[a-zA-Z0-9áéíóúñÑ]+$/');
    }
    public static function isAlphaNumericAndSpaces($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/');
    }
    public static function isDNI($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[0-9]{8}$/');
    }
    public static function isRUC($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[0-9]{11}$/');
    }
    public static function isUbigeo($value)
    {
        if(!self::isInputText($value)) { return false; }
        return self::isRegex((string) $value, '/^[0-9]{6}$/');
    }
    public static function isRegex($value, $regex)
    {
        if(!self::isInputText($value)) { return false; }
        return preg_match($regex, $value);
    }
    public static function isEmail($value)
    {
        if(!self::isInputText($value)) { return false; }
        return filter_var((string) $value, FILTER_VALIDATE_EMAIL);
    }
    public static function isUrl($value)
    {
        if(!self::isInputText($value)) { return false; }
        return filter_var((string) $value, FILTER_VALIDATE_URL);
    }
    
    /* Numbers */
    public static function isInt($value) { return filter_var($value, FILTER_VALIDATE_INT); }
    public static function isFloat($value)
    {
        $decimal = '.';
        $decimal = array('decimal' => $decimal);
        $options = array('options' => $decimal);
        return filter_var($value, FILTER_VALIDATE_FLOAT, $options);
    }
    public static function isPositive($value)
    {
        if(!self::isFloat($value)) { return false; }
        return $value >= 0;
    }
    public static function isBetween($value, $min, $max)
    {
        if(!self::isFloat($value)) { return false; }
        return $value >= $min && $value <= $max;
    }
    
    /* Arrays */
    public static function isArray($value) { return is_array($value); }
    public static function hasElements($value = array())
    {
        if(!self::isArray($value)) { return false; }
        return sizeof($value) > 0;
    }
    public static function hasUniqueValues($value)
    {
        if(!self::isArray($value)) { return false; }

        $aux = [];
        foreach ($value as $element)
        {
            $serialized_element = crc32(serialize($element));
            if(isset($aux[$serialized_element])) { return false; }
            else { $aux[$serialized_element] = 1; }
        }
        return true;
    }

    /* Dates */
    public static function isDate($value)
    {
        $format = 'Y-m-d';
        $tmp = \DateTime::createFromFormat($format, $value);
        return $tmp && $$tmp->format($format) == $value;
    }

    public static function isDateTime($value)
    {
        $format = 'Y-m-d H:i:s';
        $tmp = \DateTime::createFromFormat($format, $value);
        return $tmp && $$tmp->format($format) == $value;
    }

    public static function isTimestamp($value) { return self::isInt($value) && self::isPositive($value) && $value <= PHP_INT_MAX; }

    /* General */
    public static function isDifferentTo($value, $another_value) { return $value != $another_value; }
    public static function isIn($value, $array)
    {
        if(!self::isArray($array)) { return false; }
        return in_array($value, $array);
    }

    /* DB */
    public static function isUnique($value, $table, $column, $condition = '1')
    {
        $extras_dao = new \ExtrasDAO();
        return $extras_dao->isUnique($value, $table, $column, $condition);
    }

    public static function rowExists($table, $field, $value)
    {
        $extras_dao = new \ExtrasDAO();
        return $extras_dao->rowExists($table, $field, $value);
    }
}
