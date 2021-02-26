<?php
namespace modules\patata\validator;

abstract class Rule
{
	/*public static function isSomething($value)
    {
        if(true) { return true; }
        else { return false; }
    }*/

    public static function isEmail($value) { return filter_var($value, FILTER_VALIDATE_EMAIL); }
    public static function isFloat($value, $decimal = '.')
    {
        $decimal = array('decimal' => $decimal);
        $options = array('options' => $decimal);
        return filter_var($value, FILTER_VALIDATE_FLOAT, $options);
    }
    public static function isInt($value) { return filter_var($value, FILTER_VALIDATE_INT); }
    public static function isBetween($value, $min, $max, $decimal = '.')
    {
        if(self::isFloat($min, $decimal) && self::isFloat($max, $decimal) && $value > $min && $value < $max) { return true; }
        else { return false; }
    }
    public static function isUrl($value) { return filter_var($value, FILTER_VALIDATE_URL); }
    public static function isFilled($value)
    {
        if(strlen(trim($value)) > 0) { return true; }
        else { return false; }
    }
    public static function isObject($value)
    {
        if(is_object($value)) { return true; }
        else { return false; }
    }
    public static function isPositive($value)
    {
        if(self::isFloat($value) && $value > 0 ) { return true; }
        else { return false; }
    }
    public static function isRegex($value, $regex) { return preg_match($regex, $value); }
    public static function isWord($value) { return $x = self::isRegex($value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/'); print_r($x); self::isRegex($value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/'); }
    public static function isWords($value)
    {
        if(self::isFilled($value)) { return self::isRegex($value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/'); }
        else { return false; }
    }
    public static function isAlphaNumeric($value) { return self::isRegex($value, '/^[a-zA-Z0-9áéíóúñÑ]+$/'); }
    public static function isAlphaNumericAndSpaces($value)
    {
        if(self::isFilled($value)) { return self::isRegex($value, '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/'); }
        else { return false; }
    }
    public static function lengthIsLessThan($value, $size) { return strlen($value) < $size; }
    public static function lengthIsGreaterThan($value, $size) { return strlen($value) > $size; }
    public static function isIn($value, $array) { return in_array($value, $array); }
    public static function hasElements($array = array())
    {
        if(!is_array($array)) { return false; }
        if(sizeof($array) == 0) { return false; }
        else { return true; }
    }
    public static function hasUniqueValues($array = array())
    {
        if(is_array($array))
        {
            $aux = array();
            foreach ($array as $val)
            {
                $index = crc32(serialize($val));
                if(isset($aux[$index])) { return false; }
                else { $aux[$index] = 1; }
            }
            return true;
        }
        else { return true; }
    }
    public static function isDNI($value) { return self::isRegex($value, '/^[0-9]{8}$/'); }

    public static function isUnique($value, $table, $column, $condition = '1')
    {
        $extras_dao = new \ExtrasDAO();
        return $extras_dao->isUnique($value, $table, $column, $condition);
    }

    public static function fileWasUploaded($value)
    {
        if(isset($value['tmp_name']) && $value['error'] != 1) { return true; }
        else { return false; }
    }

    public static function sizeIsLessThan($value, $maxSize)
    {
        if($value['size'] > $maxSize) { return false; }
        else { return true; }
    }

    public static function typeIsInArray($value, $allowedTypes)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $value['tmp_name']);
        if(in_array($mime, $allowedTypes)) { return true; }
        else { return false; }
    }
}
