<?php
class TerminalDAO
{
    private static $table = 'terminales';
    private static $pk = 'term_id';

    public static function getSelectedFields($alias = NULL, $prefix = '')
    {
        $alias = $alias ? $alias . '.' : '';

        return [
            $alias . 'term_id' => $prefix . 'id',
            $alias . 'term_nombre' => $prefix . 'nombre',
            $alias . 'term_habilitado' => $prefix . 'habilitado',
        ];
    }

    private static function getFieldsToInsert(Terminal $terminal)
    {
        $fields = 
        [
            'term_nombre' => $terminal->getNombre()
        ];

        if($terminal->getId() == NULL) { $fields['term_habilitado'] = $terminal->getHabilitado(); }

        return $fields;
    }

    private function processRow(&$row, $key, $params = [])
    {
        $row->id = (int) $row->id;
        
        
        
        $row->habilitado = (bool) $row->habilitado;

        $cast = isset($params['cast']) ? $params['cast'] : false;

        if($cast) { $row = TerminalHelper::castToTerminal($row, $row->id); }
    }

    public function selectAll($cast = true)
    {
        $results = Repository::getDB()->select(self::$table, self::getSelectedFields(), '', [], 'ORDER BY ' . self::$pk . ' DESC');
        array_walk($results, [$this, 'processRow'], ['cast' => $cast]);
        return ['data' => $results, 'total' => sizeof($results)];
    }

    public function selectCerradas($cast = false)
    {
        $results = Repository::getDB()->select(self::$table, self::getSelectedFields(), 'term_habilitado = 1 AND term_id NOT IN (SELECT caja_term_id FROM cajas WHERE caja_cierre IS NULL)');
        array_walk($results, [$this, 'processRow'], ['cast' => $cast]);
        return ['data' => $results, 'total' => sizeof($results)];
    }

    public function selectById($id, $cast = true)
    {
        $result = Repository::getDB()->selectOne(self::$table, self::getSelectedFields(), 'term_id = :id', ['id' => $id]);
        if($result) { $this->processRow($result, 0, ['cast' => $cast]); }
        return $result;
    }

    public function insert(Terminal $terminal)
    {
        $db = Repository::getDB();
        $db->insert(self::$table, self::getFieldsToInsert($terminal));
        $terminal->setId($db->getLastInsertId());
        return true;
    }

    public function update(Terminal $terminal)
    {
        return Repository::getDB()->update(self::$table, self::getFieldsToInsert($terminal), 'term_id = :id', ['id' => $terminal->getId()]);
    }

    public function delete($id) { return Repository::getDB()->delete(self::$table, 'term_id = :id', ['id' => $id]); }

    public function setHabilitado($id, $habilitado)
    {
        return Repository::getDB()->update(self::$table, ['term_habilitado' => $habilitado], 'term_id = :id', ['id' => $id]);
    }
}
