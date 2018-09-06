<?php
require_once(LIBRARIES . 'DB/DB.php');
require_once(MODEL . 'business/Autor.php');
use DB\DB;

class AutorModel extends Autor
{
    private static $table = 'autores';
    private static $table_libros_autores = 'libros_autores';

    public static function selectAll()
    {
        $db = new DB();
        $fields = array
        (
            'auto_id' => 'id', 
            'auto_nombre' => 'nombre'
        );
        return $db->select(self::$table, $fields);
    }

    public static function selectById($id)
    {
        $db = new DB();
        $fields = array
        (
            'auto_nombre' => 'nombre'
        );
        $where = 'auto_id = :id';
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
                    'auto_nombre' => $this->_nombre
                );
        $db->insert(self::$table, $data);
        return $db->getLastInsertId();
    }

    public function update()
    {
        $db = new DB();
        $replacements = array('auto_nombre' => $this->_nombre);
        $where = 'auto_id = :id_to_modify';
        $data = array('id_to_modify' => $this->_id);
        $db->update(self::$table, $replacements, $where, $data);
        return $db->rowCount();
    }

    public function delete()
    {
        $db = new DB();
        $where = 'auto_id = :id_to_delete';
        $data = array('id_to_delete' => $this->_id);
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }

    public function isUsedByLibrosAutores()
    {
        $db = new DB();
        $sql = 'SELECT  COUNT(*) AS amount 
                FROM    ' . self::$table_libros_autores . ' 
                WHERE   liau_auto_id = :id';
        $data = array('id' => $this->_id);
        $db->query($sql, $data);
        $result = $db->fetchArray();
        if($result['amount'] == 0) { return false; }
        else { return true; }
    }

    public static function getByLibroId($id)
    {
        $db = new DB();
        $sql = 'SELECT  auto_id AS id, 
                        auto_nombre AS nombre 
                FROM    libros_autores 
                JOIN    autores 
                ON      liau_auto_id = auto_id 
                WHERE   liau_libr_id = :libr_id';

        $data = array('libr_id' => $id);
        $db->query($sql, $data);
        return $db->fetchArrayAll();
    }

    public function addToLibro($libro_id, $db)
    {
        $data = array
                (
                    'liau_libr_id' => $libro_id, 
                    'liau_auto_id' => $this->_id
                );
        $db->insert(self::$table_libros_autores, $data);
    }

    public static function deleteFromLibro($libro_id, $db)
    {
        $where = 'liau_libr_id = :id_to_delete';
        $data = array('id_to_delete' => $libro_id);
        $db->delete(self::$table_libros_autores, $where, $data);
    }
}
