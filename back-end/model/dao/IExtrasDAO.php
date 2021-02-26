<?php
interface IExtrasDAO
{
	public function getEnumValues($table, $field);
	public function isUnique($value, $table, $column, $condition);
}