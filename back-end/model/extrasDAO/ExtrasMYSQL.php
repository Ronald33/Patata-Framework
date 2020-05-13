<?php
use DB\DB;

class ExtrasMYSQL
{
    private function getField($table, $field)
	{
        $db = DB::getInstance();
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
}
