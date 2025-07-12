<?php
class ExtrasDAO
{
    public function isUnique($value, $table, $column, $condition = '1')
    {
        $result = Repository::getDB()->selectOne($table, 'COUNT(1) AS total', $column . ' = :value AND ' . $condition, ['value' => $value]);
        return (int) $result->total == 0;
    }
}