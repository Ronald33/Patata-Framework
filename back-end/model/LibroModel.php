<?php
require_once(LIBRARIES . 'DB/DB.php');
require_once(MODEL . 'business/Libro.php');
require_once(MODEL . 'AutorModel.php');
use DB\DB;

class LibroModel extends Libro
{
    private static $table = 'libros';

    public static function selectAll()
    {
        $db = new DB();
        $fields = array
        (
            'libr_id' => 'id', 
            'libr_titulo' => 'titulo', 
            'libr_descripcion' => 'descripcion', 
            'libr_paginas' => 'paginas', 
            'libr_estado' => 'estado', 
            'libr_fecha_ingreso' => 'fecha_ingreso', 
            'libr_edit_id' => 'editorial'
        );
        return $db->select(self::$table, $fields);
    }

    public static function selectById($id)
    {
        $db = new DB();
        $fields = array
        (
            'libr_titulo' => 'titulo', 
            'libr_descripcion' => 'descripcion', 
            'libr_paginas' => 'paginas', 
            'libr_estado' => 'estado', 
            'libr_fecha_ingreso' => 'fecha_ingreso', 
            'libr_edit_id' => 'editorial'
        );
        $where = 'libr_id = :id';
        $replacements = array('id' => $id);
        $result = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($result) == 1)
        {
            $result[0]->autores = AutorModel::getByLibroId($id);
            return $result[0];
        }
        else { return NULL; }
    }

    public function insert()
    {
        $db = new DB();
        $db->beginTransaction();
        $data = array
                (
                    'libr_titulo' => $this->_titulo, 
                    'libr_descripcion' => $this->_descripcion, 
                    'libr_paginas' => $this->_paginas, 
                    'libr_fecha_ingreso' => $this->_fecha_ingreso, 
                    'libr_edit_id' => isset($this->_editorial) ? $this->_editorial->getId() : NULL
                );
        if(isset($this->_estado)) { $data['libr_estado'] = $this->_estado; }
        $db->insert(self::$table, $data);
        $this->setId($db->getLastInsertId());
        $this->addAutores($db);
        $db->commit();
        return $this->getId();
    }

    public function update()
    {
        $db = new DB();
        $db->beginTransaction();
        $replacements = array
        (
            'libr_titulo' => $this->_titulo, 
            'libr_descripcion' => $this->_descripcion, 
            'libr_paginas' => $this->_paginas, 
            'libr_fecha_ingreso' => $this->_fecha_ingreso, 
            'libr_edit_id' => isset($this->_editorial) ? $this->_editorial->getId() : NULL
        );
        if(isset($this->_estado)) { $data['libr_estado'] = $this->_estado; }
        $where = 'libr_id = :id_to_modify';
        $data = array('id_to_modify' => $this->_id);
        $db->update(self::$table, $replacements, $where, $data);
        $this->deleteAutores($db);
        $this->addAutores($db);
        $db->commit();
        return $db->rowCount();
    }

    public function delete()
    {
        $db = new DB();
        $db->beginTransaction();
        $this->deleteAutores($db);
        $where = 'libr_id = :id_to_delete';
        $data = array('id_to_delete' => $this->_id);
        $db->delete(self::$table, $where, $data);
        $db->commit();
        return $db->rowCount();
    }

    public function addAutores($db)
    {
        $autor_size = sizeof($this->_autores);
        for($i=0; $i < $autor_size; $i++) { $this->_autores[$i]->addToLibro($this->_id, $db); }
    }

    public function deleteAutores($db) { AutorModel::deleteFromLibro($this->_id, $db); }
}
