<?php
namespace Core\Helper;

class Helper
{
	/*
		Example: http://localhost/my_project
		Return: my_project
	*/
	public static function getFolder()
	{
		$self = $_SERVER['PHP_SELF'];
		$folder = dirname($self);
		if($folder == '/') { return ''; }
		else { return $folder; }
	}

	/*
		Example: http://localhost/my_project
		Return: http://localhost/my_project/
	*/
	public static function getURLBase()
	{
		//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$protocol = 'http';
		return $protocol . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . self::getFolder();
	}
}
