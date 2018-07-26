<?php
namespace Render;
abstract class PDictionary
{
	public static function getSuperData()
	{
		return array
		(
			'URL_BASE' => \URL_BASE, 
			'FRONT_END' => \FRONT_END
		);
	}
}
