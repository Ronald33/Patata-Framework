<?php
class UsuarioDAO
{
    private static $table = 'usuarios';

    private static $selected_fields = 
    [
        'usua_id' => 'id',
        'usua_usuario' => 'usuario',
  		//'usua_contrasenha' => 'contrasenha',
        'usua_habilitado' => 'habilitado', 
        'usua_tipo' => 'tipo', 
        'usua_pers_id' => 'pers_id'
    ];

	private static function getFieldsToInsert(Usuario $usuario)
	{
        $fields = 
        [
			'usua_usuario' => $usuario->getUsuario(),
			'usua_tipo' => strtoupper(get_class($usuario)),
            'usua_habilitado' => $usuario->getHabilitado(), 
            'usua_pers_id' => $usuario->getPersona()->getId()
        ];

        if($usuario->getContrasenha()) { $fields['usua_contrasenha'] = $usuario->getContrasenha(); }

		return $fields;
    }
    
    // Function for modify each value of row
    private function processRow(&$row, $key, $params = [])
    {
        $cast = isset($params['cast']) ? $params['cast'] : false;
        $set_sub_items = isset($params['set_sub_items']) ? $params['set_sub_items'] : false;

        if($set_sub_items)
        {
            $dao_persona = new PersonaDAO();
            $row->persona = $dao_persona->selectById($row->pers_id, $cast);
            unset($row->pers_id);
        }
        
        $row->id = (int) $row->id;

        if($cast) { $row = UsuarioHelper::castToUsuario($row); }
    }

    public function selectAll($cast = true, $set_sub_items = true)
    {
        $db = Repository::getDB();
        $results = $db->select(self::$table, self::$selected_fields);
        array_walk($results, [$this, 'processRow'], ['cast' => $cast, 'set_sub_items' => $set_sub_items]);
        return $results;
    }
    
    public function selectById($id, $cast = true, $set_sub_items = true)
    {
        $db = Repository::getDB();
        $result = $db->selectOne(self::$table, self::$selected_fields, 'usua_id = :id', ['id' => $id]);
        if($result) { $this->processRow($result, 0, ['cast' => $cast, 'set_sub_items' => $set_sub_items]); }
        return $result;
    }

    public function selectByUserAndPassword($usuario, $contrasenha, $cast = true, $set_sub_items = true)
    {
        $db = Repository::getDB();
        $where = 'usua_habilitado = 1 AND usua_usuario = :usuario AND usua_contrasenha = :contrasenha';
        $replacements = ['usuario' => $usuario, 'contrasenha' => md5($contrasenha)];
        $result = $db->selectOne(self::$table, self::$selected_fields, $where, $replacements);
        if($result) { $this->processRow($result, 0, ['cast' => $cast, 'set_sub_items' => $set_sub_items]); }
        return $result;
    }

    public function insert(Usuario $usuario)
    {
        $db = Repository::getDB();
        $db->beginTransaction();
        $persona = $usuario->getPersona();
        if($persona->getId() == NULL) { $dao_persona = new PersonaDAO(); $dao_persona->insert($persona); }
        $data = self::getFieldsToInsert($usuario);
        $db->insert(self::$table, $data);
        $usuario->setId($db->getLastInsertId());
        $db->commit();
    }

    public function update(Usuario $usuario)
    {
        $db = Repository::getDB();
        $replacements = self::getFieldsToInsert($usuario);

        $where = 'usua_id = :id';
        $data = ['id' => $usuario->getId()];
        $db->update(self::$table, $replacements, $where, $data);
    }

    public function delete($id)
    {
        $db = Repository::getDB();
        $where = 'usua_id = :id';
        $data = ['id' => $id];
        $db->delete(self::$table, $where, $data);
    }

    public function setHabilitado($id, $habilitado)
    {
        $db = Repository::getDB();
        $replacements = ['usua_habilitado' => $habilitado];
        $where = 'usua_id = :id';
        $data = ['id' => $id];
        $db->update(self::$table, $replacements, $where, $data);
    }
}