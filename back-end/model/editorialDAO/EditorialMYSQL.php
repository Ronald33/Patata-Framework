<?php
require_once(MODEL . 'editorialDAO/IEditorialDAO.php');
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class EditorialMYSQL implements IEditorialDAO
{
    private static $table = 'editoriales';

    public function selectAll()
    {
        $db = new DB();
        $fields = array
        (
            'edit_id' => 'id',
            'edit_nombre' => 'nombre'
        );
        return $db->select(self::$table, $fields);
  	}
    public function selectById($id)
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

    public function insert(Editorial $editorial)
    {
        $db = new DB();
        $data = array
                (
                    'edit_nombre' => $editorial->getNombre()
                );
        $db->insert(self::$table, $data);
        return $db->getLastInsertId();
    }

    public function update(Editorial $editorial)
    {
        $db = new DB();
        $replacements = array('edit_nombre' => $editorial->getNombre());
        $where = 'edit_id = :id_to_modify';
        $data = array
        (
            'id_to_modify' => $editorial->getId()
        );
        $db->update(self::$table, $replacements, $where, $data);
        return $db->rowCount();
    }

    public function delete(Editorial $editorial)
    {
        $db = new DB();
        $where = 'edit_id = :id_to_delete';
        $data = array('id_to_delete' => $editorial->getId());
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }
}