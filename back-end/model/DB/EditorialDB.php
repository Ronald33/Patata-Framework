<?php
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class EditorialDB
{
    private static $table = 'editoriales';

    protected $_id;
    protected $_nombre;
    
    public function insert()
    {
        $db = new DB();
        $data = array
                (
                    'edit_nombre' => $this->_nombre
                );
        $db->insert(self::$table, $data);
    }

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
            'edit_id' => 'id', 
            'edit_nombre' => 'nombre'
        );
        $where = 'edit_id = :id';
        $replacements = array('id' => $id);
        $result = $db->select(self::$table, $fields, $where, $replacements);
        return $db->select(self::$table, $fields, $where, $replacements);
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

    public static function delete($id)
    {
        $db = new DB();
        $where = 'edit_id = :id_to_delete';
        $data = array
        (
            'id_to_delete' => $id
        );
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }
}