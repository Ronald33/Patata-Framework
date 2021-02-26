<?php
namespace modules\patata\uploader;

abstract class Helper
{
    public static function getCurrentTimestamp()
	{
        $config = parse_ini_file(__DIR__ . '/config.ini');
		$now = new \DateTime('', new \DateTimeZone($config['ZONA_HORARIA']));
		return $now->getTimestamp();
	}
}