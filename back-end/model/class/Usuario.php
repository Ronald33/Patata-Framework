<?php

abstract class Usuario
{
    protected $id;

    protected $usuario;

    protected $contrasenha;

    protected $habilitado;

    protected $persona;

    public function __construct()
    {
        // ...
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of usuario
     */ 
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */ 
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get the value of contrasenha
     */ 
    public function getContrasenha()
    {
        return $this->contrasenha;
    }

    /**
     * Set the value of contrasenha
     *
     * @return  self
     */ 
    public function setContrasenha($contrasenha)
    {
        $this->contrasenha = md5($contrasenha);

        return $this;
    }

    /**
     * Get the value of habilitado
     */ 
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set the value of habilitado
     *
     * @return  self
     */ 
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get the value of persona
     */ 
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set the value of persona
     *
     * @return  self
     */ 
    public function setPersona(Persona $persona)
    {
        $this->persona = $persona;

        return $this;
    }
}
