<?php
require_once(__DIR__ . '/../dao/IPersonaDAO.php');

class PersonaDAO implements IPersonaDAO
{
    private static $table = 'personas';

    private static $selected_fields = array
    (
        'pers_id' => 'id',
        'pers_nombres' => 'nombres',
        'pers_apellidos' => 'apellidos',
        'pers_documento' => 'documento',
        'pers_email' => 'email', 
        'pers_telefono' => 'telefono',
        'pers_direccion' => 'direccion'
    );

	private static function getFieldsToInsert(Persona $persona)
	{
        $fields = array
		(
			'pers_nombres' => $persona->getNombres(),
			'pers_apellidos' => $persona->getApellidos(),
			'pers_documento' => $persona->getDocumento(),
            'pers_email' => $persona->getEmail(), 
			'pers_telefono' => $persona->getTelefono(),
            'pers_direccion' => $persona->getDireccion()
        );

		return $fields;
    }
    
    private function setSubItems(&$row)
    {
        
    }

    public function selectAll()
    {
        $db = Repository::getDB();
        $results = $db->select(self::$table, self::$selected_fields);
        array_walk($results, array($this, 'setSubItems'));
        return $results;
    }

    public function selectById($id)
    {
        $db = Repository::getDB();
        $fields = self::$selected_fields;
        $where = 'pers_id = :id';
        $replacements = array('id' => $id);
        $results = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($results) == 1) { $this->setSubItems($results[0]); return $results[0]; }
        else { return NULL; }
    }

    public function selectFiltered($filter)
    {
        $db = Repository::getDB();
        $where = 'pers_nombres LIKE :filter OR pers_apellidos LIKE :filter OR pers_documento LIKE :filter';
        $replacements = ['filter' => '%' . $filter . '%'];
        $results = $db->select(self::$table, self::$selected_fields, $where, $replacements);
        array_walk($results, array($this, 'setSubItems'));
        return $results;
    }

    // public function selectByDocumento($documento)
    // {
    //     $db = Repository::getDB();
    //     $where = 'pers_documento = :documento';
    //     $replacements = array('documento' => $documento);
    //     $results = $db->select(self::$table, self::$selected_fields, $where, $replacements);
    //     if(sizeof($results) == 1) { return $results[0]; }
    //     else { return NULL; }
    // }

    public function insert(Persona $persona)
    {
        $db = Repository::getDB();
        $data = self::getFieldsToInsert($persona);
        $db->insert(self::$table, $data);
        $persona->setId($db->getLastInsertId());
    }

    public function update(Persona $persona)
    {
        $db = Repository::getDB();
        $replacements = self::getFieldsToInsert($persona);
        $where = 'pers_id = :id';
        $data = array('id' => $persona->getId());
        $db->update(self::$table, $replacements, $where, $data);
    }

    public function delete($id)
    {
        $db = Repository::getDB();
        $where = 'pers_id = :id';
        $data = array('id' => $id);
        $db->delete(self::$table, $where, $data);
    }
}