<?php
namespace Render;
require_once(LIBRARIES . '/Render/core/PDictionary.php');
abstract class Dictionary extends PDictionary
{
	public static function get()
	{
		return array
		(
			'title' => \TITLE, 
			'description' => \DESCRIPTION
		);
	}
}
