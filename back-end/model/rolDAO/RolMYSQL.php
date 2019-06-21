<?php
require_once(MODEL . 'rolDAO/IRolDAO.php');
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class RolMYSQL implements IRolDAO
{
    private static $table = 'roles';

    private static $selected_fields = array
    (
        'role_id' => 'id',
        'role_nombre' => 'nombre'
    );

    public function getAll()
    {
        $db = DB::getInstance();
        return $db->select(self::$table, self::$selected_fields);
  	}

    public function getById($id)
    {
        $db = DB::getInstance();
        $fields = self::$selected_fields;
        $where = 'role_id = :id';
        $replacements = array('id' => $id);
        $results = $db->select(self::$table, $fields, $where, $replacements);
        if (sizeof($results) == 1) { return $results[0]; }
        else { return null; }
    }
}
