<?php
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class ExtrasMYSQL
{
    public function getField($table, $field)
	{
        $db = new DB();
        $sql = 'SHOW    COLUMNS 
                FROM    ' . $table . ' 
                WHERE   Field = :field';
        $data = array('field' => $field);
        $db->query($sql, $data);
        return $db->fetchArray();
	}
}
