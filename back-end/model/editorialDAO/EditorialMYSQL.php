<?php
require_once(MODEL . 'editorialDAO/IEditorialDAO.php');
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class EditorialMYSQL implements IEditorialDAO
{
    private static $table = 'editoriales';
    
    private static $selected_fields = array
	(
		'edit_nombre' => 'nombre'
	);
	
	private static function getFieldsToInsert(Editorial $editorial)
	{
		$fields = array
		(
			'edit_nombre' => $editorial->getNombre()
		);
		return $fields;
	}

    public function selectAll()
    {
        $db = DB::getInstance();
        $fields = self::$selected_fields;
        $fields['edit_id'] = 'id';
        return $db->select(self::$table, $fields);
  	}
    public function selectById($id)
    {
        $db = DB::getInstance();
        $fields = self::$selected_fields;
        $where = 'edit_id = :id';
        $replacements = array('id' => $id);
        $result = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($result) == 1) { return $result[0]; }
        else { return NULL; }
    }

    public function insert(Editorial $editorial)
    {
        $db = DB::getInstance();
        $data = self::getFieldsToInsert($editorial);
        $db->insert(self::$table, $data);
        return $db->getLastInsertId();
    }

    public function update(Editorial $editorial)
    {
        $db = DB::getInstance();
        $replacements = self::getFieldsToInsert($editorial);
        $where = 'edit_id = :id_to_modify';
        $data = array('id_to_modify' => $editorial->getId());
        $db->update(self::$table, $replacements, $where, $data);
        return $db->rowCount();
    }

    public function delete(Editorial $editorial)
    {
        $db = DB::getInstance();
        $where = 'edit_id = :id_to_delete';
        $data = array('id_to_delete' => $editorial->getId());
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }
}
