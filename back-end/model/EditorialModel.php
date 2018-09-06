<?php
require_once(LIBRARIES . 'DB/DB.php');
require_once(MODEL . 'business/Editorial.php');
use DB\DB;

class EditorialModel extends Editorial
{
    private static $table = 'editoriales';

    public static function selectAll()
    {
        $db = new DB();
        $fields = array
        (
            'edit_id' => 'id', 
            'edit_nombre' => 'nombre'
        );
        return $db->select(self::$table, $fields);
    }

    public static function selectById($id)
    {
        $db = new DB();
        $fields = array
        (
            'edit_nombre' => 'nombre'
        );
        $where = 'edit_id = :id';
        $replacements = array('id' => $id);
        $result = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($result) == 1) { return $result[0]; }
        else { return NULL; }
    }

    public function insert()
    {
        $db = new DB();
        $data = array
                (
                    'edit_nombre' => $this->_nombre
                );
        $db->insert(self::$table, $data);
        return $db->getLastInsertId();
    }

    public function update()
    {
        $db = new DB();
        $replacements = array('edit_nombre' => $this->_nombre);
        $where = 'edit_id = :id_to_modify';
        $data = array
        (
            'id_to_modify' => $this->_id
        );
        $db->update(self::$table, $replacements, $where, $data);
        return $db->rowCount();
    }

    public function delete()
    {
        $db = new DB();
        $where = 'edit_id = :id_to_delete';
        $data = array('id_to_delete' => $this->_id);
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }
}
