<?php
namespace Validate;
abstract class Helper
{
	public static function existsRule($rule)
	{
		if(is_callable(array(__NAMESPACE__ . '\Rule', $rule))) { return true; }
		else { return false; }
	}
}