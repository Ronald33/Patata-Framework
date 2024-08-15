<?php
class PersonaDAO
{
    private static $table = 'personas';

    private static $selected_fields = 
    [
        'pers_id' => 'id',
        'pers_nombres' => 'nombres',
        'pers_apellidos' => 'apellidos',
        'pers_documento' => 'documento',
        'pers_email' => 'email', 
        'pers_telefono' => 'telefono',
        'pers_direccion' => 'direccion'
    ];

	private static function getFieldsToInsert(Persona $persona)
	{
        $fields = 
        [
			'pers_nombres' => $persona->getNombres(),
			'pers_apellidos' => $persona->getApellidos(),
			'pers_documento' => $persona->getDocumento(),
            'pers_email' => $persona->getEmail(), 
			'pers_telefono' => $persona->getTelefono(),
            'pers_direccion' => $persona->getDireccion()
        ];

		return $fields;
    }

    // Function for modify each value of row
    private function processRow(&$row, $key, $params = [])
    {
        $cast = isset($params['cast']) ? $params['cast'] : false;
        
        $row->id = (int) $row->id;

        if($cast) { $row = Helper::cast('Persona', $row); }
    }

    public function selectAll($cast = true, $set_sub_items = true)
    {
        $db = Repository::getDB();
        $results = $db->select(self::$table, self::$selected_fields);
        array_walk($results, [$this, 'processRow'], ['cast' => $cast, 'set_sub_items' => $set_sub_items]);
        return $results;
    }

    public function selectById($id, $cast = true)
    {
        $db = Repository::getDB();
        $result = $db->selectOne(self::$table, self::$selected_fields, 'pers_id = :id', ['id' => $id]);
        if($result) { $this->processRow($result, 0, ['cast' => $cast]); }
        return $result;
    }

    public function selectByWildcard($wildcard, $cast = true, $set_sub_items = true)
    {
        $db = Repository::getDB();
        $where = 'pers_nombres LIKE :wildcard OR pers_apellidos LIKE :wildcard OR pers_documento LIKE :wildcard';
        $replacements = ['wildcard' => '%' . $wildcard . '%'];
        $results = $db->select(self::$table, self::$selected_fields, $where, $replacements);
        array_walk($results, [$this, 'processRow'], ['cast' => $cast, 'set_sub_items' => $set_sub_items]);
        return $results;
    }

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
        $db->update(self::$table, $replacements, 'pers_id = :id', ['id' => $persona->getId()]);
    }

    public function delete($id)
    {
        $db = Repository::getDB();
        $where = 'pers_id = :id';
        $data = ['id' => $id];
        $db->delete(self::$table, $where, $data);
    }
}