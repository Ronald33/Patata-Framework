<?php
require_once(__DIR__ . '/../dao/IUsuarioDAO.php');

class UsuarioDAO implements IUsuarioDAO
{
    private static $table = 'usuarios';

    private static $selected_fields = array
    (
        'usua_id' => 'id',
        'usua_usuario' => 'usuario',
  		//'usua_contrasenha' => 'contrasenha',
        'usua_habilitado' => 'habilitado', 
        'usua_tipo' => 'tipo', 
        'usua_pers_id' => 'pers_id'
    );

	private static function getFieldsToInsert(Usuario $usuario)
	{
        $fields = array
		(
			'usua_usuario' => $usuario->getUsuario(),
			'usua_tipo' => strtoupper(get_class($usuario)),
            'usua_habilitado' => $usuario->getHabilitado(), 
            'usua_pers_id' => $usuario->getPersona()->getId()
        );

        if($usuario->getContrasenha()) { $fields['usua_contrasenha'] = $usuario->getContrasenha(); }

		return $fields;
    }
    
    private function setSubItems(&$row)
    {
        $dao_persona = new PersonaDAO();
        $row->persona = $dao_persona->selectById($row->pers_id);
        unset($row->pers_id);
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
        $where = 'usua_id = :id';
        $replacements = array('id' => $id);
        $results = $db->select(self::$table, $fields, $where, $replacements);
        if(sizeof($results) == 1) { $this->setSubItems($results[0]); return $results[0]; }
        else { return NULL; }
    }

    public function selectByUserAndPassword($usuario, $contrasenha)
    {
        $db = Repository::getDB();
        $fields = self::$selected_fields;
        $where = 'usua_habilitado = 1 AND usua_usuario = :usuario AND usua_contrasenha = :contrasenha';
        $replacements = array('usuario' => $usuario, 'contrasenha' => md5($contrasenha));
        $results = $db->select(self::$table, $fields, $where, $replacements);

        if(sizeof($results) == 1)
        {
            $result = $results[0];
            $this->setSubItems($result);
            return $result;
        }
        else { return NULL; }
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
        $data = array('id' => $usuario->getId());
        $db->update(self::$table, $replacements, $where, $data);
    }

    public function delete($id)
    {
        $db = Repository::getDB();
        $where = 'usua_id = :id';
        $data = array('id' => $id);
        $db->delete(self::$table, $where, $data);
    }

    public function setHabilitado($id, $habilitado)
    {
        $db = Repository::getDB();
        $replacements = array('usua_habilitado' => $habilitado);
        $where = 'usua_id = :id';
        $data = array('id' => $id);
        $db->update(self::$table, $replacements, $where, $data);
    }
}