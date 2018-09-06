<?php
require_once(MODEL . 'EditorialModel.php');
require_once(MODEL . 'AutorModel.php');

class Libro
{
    protected $_id;
    protected $_titulo;
    protected $_descripcion;
    protected $_paginas;
    protected $_estado;
    protected $_fecha_ingreso;
    protected $_editorial;
    protected $_autores = array();

    public function __construct($id = null)
    {
        $this->_id = $id;
    }

    public function getId(){ return $this->_id; }
    public function setId($id){ $this->_id = $id; }

    public function getTitulo(){ return $this->_titulo; }
    public function setTitulo($titulo){ $this->_titulo = $titulo; }
    public function getDescripcion(){ return $this->_descripcion; }
    public function setDescripcion($descripcion){ $this->_descripcion = $descripcion; }
    public function getPaginas(){ return $this->_paginas; }
    public function setPaginas($paginas){ $this->_paginas = $paginas; }
    public function getEstado(){ return $this->_estado; }
    public function setEstado($estado){ $this->_estado = $estado; }
    public function getFechaIngreso(){ return $this->_fecha_ingreso; }
    public function setFechaIngreso($fecha_ingreso){ $this->_fecha_ingreso = $fecha_ingreso; }
    public function getEditorial(){ return $this->_editorial; }
    public function setEditorial(EditorialModel $editorial){ $this->_editorial = $editorial; }
    public function getAutores(){ return $this->_autores; }
    public function addAutor(AutorModel $autor){ array_push($this->_autores, $autor); }
    public function setAutores($autores)
    {
        $this->_autores = array();
        $autores_size = sizeof($autores);
        for ($i = 0; $i < $autores_size; $i++)
        {
            array_push($this->_autores, new AutorModel($autores[$i]));
        }
    }
}
