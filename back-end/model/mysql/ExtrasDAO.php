<?php
require_once(__DIR__ . '/../dao/IExtrasDAO.php');

class ExtrasDAO implements IExtrasDAO
{
    private function getField($table, $field)
	{
        $db = Repository::getDB();
        $sql = 'SHOW    COLUMNS
                FROM    ' . $table . '
                WHERE   Field = :field';
        $data = array('field' => $field);
        $db->query($sql, $data);
        return $db->fetchArray();
    }

    public function getEnumValues($table, $field)
    {
        $_field = $this->dao->getField($table, $field);
        preg_match('/^enum\(\'(.*)\'\)$/', $_field['Type'], $matches);
        $enum = explode("','", $matches[1]);
		return $enum;
    }

    public function isUnique($value, $table, $column, $condition = '1')
    {
        $db = Repository::getDB();
        $sql = 'SELECT  COUNT(*) AS total 
                FROM    ' . $table . ' 
                WHERE   ' . $column . '=:value 
                AND     ' . $condition;
        $data = ['value' => $value];
        $db->query($sql, $data);
        $result = $db->fetchArray();
        $total = $result['total'];
        return $total == 0;
    }
}
