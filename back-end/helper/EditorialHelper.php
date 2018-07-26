<?php
require_once(MODEL . 'Editorial.php');

abstract class EditorialHelper
{
	public static function getEditorialFromObject($object)
	{
        $editorial = new Editorial();
        if(isset($object->id)) { $editorial->setId($object->id); }
        if(isset($object->nombre)) { $editorial->setNombre($object->nombre); }
        return $editorial;
	}
}