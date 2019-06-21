<?php
/**
 * @author ronald
 */
class Rol implements JsonSerializable
{
    /**
     * @var Integer
     */
    private $id;

    /**
     * @var String
     */
    private $nombre;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * Get the value of Id
     *
     * @return Integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param Integer id
     *
     * @return self
     */
    public function setId(Integer $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of Nombre
     *
     * @return String
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of Nombre
     *
     * @param String nombre
     *
     * @return self
     */
    public function setNombre(String $nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

}
