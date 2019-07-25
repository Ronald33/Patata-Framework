<?php
use DB\DB;

class ExtrasMYSQL
{
    public function getField($table, $field)
	{
        $db = DB::getInstance();
        $sql = 'SHOW    COLUMNS
                FROM    ' . $table . '
                WHERE   Field = :field';
        $data = array('field' => $field);
        $db->query($sql, $data);
        return $db->fetchArray();
	}
}
