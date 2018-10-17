<?php
require_once(MODEL . 'libroDAO/ILibroDAO.php');
require_once(MODEL . 'autorDAO/AutorMYSQL.php');
require_once(LIBRARIES . 'DB/DB.php');
use DB\DB;

class LibroMYSQL implements ILibroDAO
{
    private static $table = 'libros';
    private static $autorDAO;
    
    private static $selected_fields = array
	(
		'libr_id' => 'id',
		'libr_titulo' => 'titulo',
		'libr_descripcion' => 'descripcion',
		'libr_paginas' => 'paginas',
		'libr_estado' => 'estado',
		'libr_fecha_ingreso' => 'fecha_ingreso',
		'libr_edit_id' => 'editorial'
	);
    
    public function __construct()
    {
		self::$autorDAO = new AutorMYSQL();
	}
	
	private static function getFieldsToInsert(Libro $libro)
	{
		$fields = array
		(
			'libr_titulo' => $libro->getTitulo(),
			'libr_descripcion' => $libro->getDescripcion(),
			'libr_paginas' => $libro->getPaginas(),
			'libr_fecha_ingreso' => $libro->getFechaIngreso(),
			'libr_edit_id' => $libro->getEditorial() == NULL ? NULL : $libro->getEditorial()->getId()
		);
        if($libro->getEstado() != NULL) { $fields['libr_estado'] = $libro->getEstado(); }
		return $fields;
	}

    public function selectAll()
    {
        $db = new DB();
        $fields = self::$selected_fields;
        $fields['libr_id'] = 'id';
        return $db->select(self::$table, $fields);
    }

    public function selectById($id)
    {
        $db = new DB();
        $fields = self::$selected_fields;
        $where = 'libr_id = :id';
        $replacements = array('id' => $id);
        $result = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($result) == 1)
        {
            $result[0]->autores = self::$autorDAO->getFromLibro(new Libro($id));
            return $result[0];
        }
        else { return NULL; }
    }

    public function insert(Libro $libro)
    {
        $db = new DB();
        $db->beginTransaction();
        $data = self::getFieldsToInsert($libro);
        $db->insert(self::$table, $data);
        $libro->setId($db->getLastInsertId());
        $this->addAutores($libro, $db);
        $db->commit();
        return $libro->getId();
    }

    public function update(Libro $libro)
    {
        $db = new DB();
        $db->beginTransaction();
        $replacements = self::getFieldsToInsert($libro);
        if($libro->getEstado() != NULL) { $data['libr_estado'] = $libro->getEstado(); }
        $where = 'libr_id = :id_to_modify';
        $data = array('id_to_modify' => $libro->getId());
        $db->update(self::$table, $replacements, $where, $data);
        $this->deleteAutores($libro, $db);
        $this->addAutores($libro, $db);
        $db->commit();
        return $db->rowCount();
    }

    public function delete(Libro $libro)
    {
        $db = new DB();
        $db->beginTransaction();
        $this->deleteAutores($libro, $db);
        $where = 'libr_id = :id_to_delete';
        $data = array('id_to_delete' => $libro->getId());
        $db->delete(self::$table, $where, $data);
        $db->commit();
        return $db->rowCount();
    }

    public function addAutores(Libro $libro, $db)
    {
        $autores = $libro->getAutores();
        $autor_size = sizeof($autores);
        for($i=0; $i < $autor_size; $i++) { self::$autorDAO->addToLibro($autores[$i], $libro, $db); }
    }

    public function deleteAutores(Libro $libro, $db) { self::$autorDAO->deleteFromLibro($libro, $db); }
}
