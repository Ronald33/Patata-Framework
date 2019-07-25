<?php
require_once(MODEL . 'autorDAO/IAutorDAO.php');
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class AutorMYSQL implements IAutorDAO
{
    private static $table = 'autores';
    private static $table_libros_autores = 'libros_autores';
    
    private static $selected_fields = array
	(
		'auto_nombre' => 'nombre'
	);
	
	private static function getFieldsToInsert(Autor $autor)
	{
		$fields = array
		(
			'auto_nombre' => $autor->getNombre()
		);
		return $fields;
	}

    public function selectAll()
    {
        $db = DB::getInstance();
        $fields = self::$selected_fields;
        $fields['auto_id'] = 'id';
        return $db->select(self::$table, $fields);
    }

    public function selectById($id)
    {
        $db = DB::getInstance();
        $fields = self::$selected_fields;
        $where = 'auto_id = :id';
        $replacements = array('id' => $id);
        $result = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($result) == 1) { return $result[0]; }
        else { return NULL; }
    }

    public function insert(Autor $autor)
    {
        $db = DB::getInstance();
        $data = self::getFieldsToInsert($autor);
        $db->insert(self::$table, $data);
        return $db->getLastInsertId();
    }

    public function update(Autor $autor)
    {
        $db = DB::getInstance();
        $replacements = self::getFieldsToInsert($autor);
        $where = 'auto_id = :id_to_modify';
        $data = array('id_to_modify' => $autor->getId());
        $db->update(self::$table, $replacements, $where, $data);
        return $db->rowCount();
    }

    public function delete(Autor $autor)
    {
        $db = DB::getInstance();
        $where = 'auto_id = :id_to_delete';
        $data = array('id_to_delete' => $autor->getId());
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }

    public function isUsedByLibrosAutores(Autor $autor)
    {
        $db = DB::getInstance();
        $sql = 'SELECT  COUNT(*) AS amount
                FROM    ' . self::$table_libros_autores . '
                WHERE   liau_auto_id = :id';
        $data = array('id' => $autor->getId());
        $db->query($sql, $data);
        $result = $db->fetchArray();
        if($result['amount'] == 0) { return false; }
        else { return true; }
    }

    public function getFromLibro(Libro $libro)
    {
        $db = DB::getInstance();
        $sql = 'SELECT  auto_id AS id,
                        auto_nombre AS nombre
                FROM    libros_autores
                JOIN    autores
                ON      liau_auto_id = auto_id
                WHERE   liau_libr_id = :libr_id';

        $data = array('libr_id' => $libro->getId());
        $db->query($sql, $data);
        return $db->fetchArrayAll();
    }

    public function addToLibro(Autor $autor, Libro $libro)
    {
		$db = DB::getInstance();
        $data = array
                (
                    'liau_libr_id' => $libro->getId(),
                    'liau_auto_id' => $autor->getId()
                );
        $db->insert(self::$table_libros_autores, $data);
    }

    public function deleteFromLibro(Libro $libro)
    {
		$db = DB::getInstance();
        $where = 'liau_libr_id = :id_to_delete';
        $data = array('id_to_delete' => $libro->getId());
        $db->delete(self::$table_libros_autores, $where, $data);
    }
}
