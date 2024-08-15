<?php
class ExtrasDAO
{
    public function getEnumValues($table, $field)
    {
        $values = [];
        $db = Repository::getDB();
        $sql = 'SHOW    COLUMNS
                FROM    ' . $table . '
                WHERE   Field = :field';
        $db->query($sql, ['field' => $field]);
        $result = $db->fetch();

        if($result)
        {
            preg_match('/^enum\(\'(.*)\'\)$/', $result->Type, $matches);
            $values = explode("','", $matches[1]);
        }

        return $values;
    }

    public function isUnique($value, $table, $column, $condition = '1')
    {
        $result = Repository::getDB()->selectOne($table, 'COUNT(1) AS total', $column . ' = :value AND ' . $condition, ['value' => $value]);
        return $result->total == 0;
    }

    public function rowExists($value, $table, $field)
    {
        return Repository::getDB()->selectOne($table, '1', $field . ' = :value', ['value' => $value]);
    }
}
