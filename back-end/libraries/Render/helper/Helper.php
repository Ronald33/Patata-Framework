<?php
namespace Render;
abstract class Helper
{
	public static function getStyle($style)
	{
		return '<link rel="stylesheet" type="text/css" href="' . $style . '" media="screen" />';
	}
	
	public static function getScript($script)
	{
		return '<script type="text/javascript" src="' . $script . '" charset="UTF-8"></script>';
	}
}