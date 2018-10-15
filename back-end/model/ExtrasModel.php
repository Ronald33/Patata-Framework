<?php
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class ExtrasModel
{
	public function getEnumValues($table, $field)
    {
        $_field = ExtrasModel::getField($table, $field);
        preg_match('/^enum\(\'(.*)\'\)$/', $_field['Type'], $matches);
        $enum = explode("','", $matches[1]);
		return $enum;
    }
    
    // Private methods
    private static function getField($table, $field)
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
