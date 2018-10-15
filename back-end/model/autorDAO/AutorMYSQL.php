<?php
require_once(MODEL . 'autorDAO/IAutorDAO.php');
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class AutorMYSQL implements IAutorDAO
{
    private static $table = 'autores';
    private static $table_libros_autores = 'libros_autores';

    public function selectAll()
    {
        $db = new DB();
        $fields = array
        (
            'auto_id' => 'id',
            'auto_nombre' => 'nombre'
        );
        return $db->select(self::$table, $fields);
    }

    public function selectById($id)
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

    public function insert(Autor $autor)
    {
        $db = new DB();
        $data = array
                (
                    'auto_nombre' => $autor->getNombre()
                );
        $db->insert(self::$table, $data);
        return $db->getLastInsertId();
    }

    public function update(Autor $autor)
    {
        $db = new DB();
        $replacements = array('auto_nombre' => $autor->getNombre());
        $where = 'auto_id = :id_to_modify';
        $data = array('id_to_modify' => $autor->getId());
        $db->update(self::$table, $replacements, $where, $data);
        return $db->rowCount();
    }

    public function delete(Autor $autor)
    {
        $db = new DB();
        $where = 'auto_id = :id_to_delete';
        $data = array('id_to_delete' => $autor->getId());
        $db->delete(self::$table, $where, $data);
        return $db->rowCount();
    }

    public function isUsedByLibrosAutores(Autor $autor)
    {
        $db = new DB();
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
        $db = new DB();
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

    public function addToLibro(Autor $autor, Libro $libro, $db)
    {
        $data = array
                (
                    'liau_libr_id' => $libro->getId(),
                    'liau_auto_id' => $autor->getId()
                );
        $db->insert(self::$table_libros_autores, $data);
    }

    public function deleteFromLibro(Libro $libro, $db)
    {
        $where = 'liau_libr_id = :id_to_delete';
        $data = array('id_to_delete' => $libro->getId());
        $db->delete(self::$table_libros_autores, $where, $data);
    }
}
