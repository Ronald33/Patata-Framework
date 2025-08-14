<?php
class Terminal implements JsonSerializable
{
    private $id;
    private $nombre;
    private $habilitado;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function getHabilitado() { return $this->habilitado; }
    public function setHabilitado($habilitado) { $this->habilitado = $habilitado; }

    public function jsonSerialize(): array { return array_merge(get_object_vars($this), ['__class' => get_class($this)]); }
}
