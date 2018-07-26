<?php
namespace Validate;

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
    public static function isDate($value, $format = 'd/m/y') { return \DateTime::createFromFormat($format, $value); }
    public static function isFilled($value)
    {
        if(strlen(trim($value)) > 0) { return true; }
        else { return false; }
    }
    public static function isPositive($value)
    {
        if(self::isFloat($value) && $value > 0 ) { return true; }
        else { return false; }
    }
    public static function isRegex($value, $regex) { return preg_match($regex, $value); }
    public static function isW($value) { return self::isRegex($value, '/^\w+$/'); }
    public static function isD($value) { return self::isRegex($value, '/^\d+$/'); }
    public static function isWord($value) { return $x = self::isRegex($value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/'); print_r($x); self::isRegex($value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/'); }
    public static function isWords($value)
    {
        if(self::isFilled($value)) { return self::isRegex($value, '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/'); }
        else { return false; }
    }
    public static function isAN($value) { return self::isRegex($value, '/^[a-zA-Z0-9áéíóúñÑ]+$/'); }
    public static function isANs($value)
    {
        if(self::isFilled($value)) { return self::isRegex($value, '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/'); }
        else { return false; }
    }
    public static function isLess($value, $size) { return strlen($value) < $size; }
    public static function isIn($value, $array) { return in_array($value, $array); }
}