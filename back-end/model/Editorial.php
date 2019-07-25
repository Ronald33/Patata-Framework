<?php
class Editorial
{
    protected $_id;
    protected $_nombre;

    public function __construct($id = null)
    {
        $this->_id = $id;
    }

    public function getId(){ return $this->_id; }
    public function setId($id){ $this->_id = $id; }

    public function getNombre(){ return $this->_nombre; }
    public function setNombre($nombre){ $this->_nombre = $nombre; }
}
