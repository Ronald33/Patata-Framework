<?php
namespace DB;
require_once(LIBRARIES . 'DB/config/config.php');
require_once(LIBRARIES . 'DB/Message.php');
require_once('core/Error/Error.php');
use Error\Error;

class DB
{
	private $db;
	private $dbh;
	private $sql;
	private $stmt;
	private $isTransaction;
	
	private static $instance;
	
	private function __construct()
	{
		try
		{
			$conf = parse_ini_file(LIBRARIES . 'DB/config/config-' . (IS_PRODUCTION ? 'prod' : 'dev') . '.ini');
			$dsn = 'mysql:dbname=' . $conf['DB_NAME'] . ';host=' . $conf['HOST'];
			$options = array
			(
				\PDO::ATTR_PERSISTENT => true, 
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
			);
			$this->dbh = new \PDO($dsn, $conf['USER'], $conf['PASSWORD'], $options);
		}
		catch(\Exception $e) { $this->showError($e); }
	}
	
	public static function getInstance()
	{
		if(self::$instance == NULL) { self::$instance = new DB(); }
		return self::$instance;
	}
	
	private function setSQL($sql) { $this->sql = $sql; }
	private function setData($data) { $this->data = $data; }
	
	public function query($sql, $data = array())
	{
		try
		{
			$this->prepare($sql);
			$this->bindValues($data);
			return $this->stmt->execute();
		}
		catch(\Exception $e) { $this->showError($e); }
	}
	
	public function insert($table, $data)
	{
		$fields = array_keys($data);

        $sql = 'INSERT INTO ' . $table . 
                '(' . implode(', ', $fields) . ') ' . 
                'VALUES ' . 
                '(:' . implode(', :', $fields) . ')';

        return $this->query($sql, $data);
	}
	
	public function select($table, $fields, $where = '', $data = array(), $limit = array())
	{
		$a_fields = array();
		if(is_array($fields))
		{
			foreach($fields as $key => $value)
			{
				if(is_string($key)) { array_push($a_fields, $key . ' AS ' . $value); }
				else { array_push($a_fields, $value); }
			}
			$fields = implode(', ', $a_fields);
		}
		
		$where = empty($where) ? '' : ' WHERE ' . $where;
		$limit = empty($limit) ? '' : ' LIMIT ' . implode(', ', $limit);
		$sql = 'SELECT ' . $fields . ' FROM ' . $table . $where . $limit;
		$this->query($sql, $data);
		return $this->fetchObjectAll();
	}
	
	public function update($table, $replacements, $where, $data = array())
	{
		$keys = array();
		$values = array();
		foreach($replacements as $key => $value)
		{
			array_push($keys, $key . ' = :' . $key);
			$values[$key] = $value;
		}
		$_keys = implode(', ', $keys);
		$sql = 'UPDATE ' . $table . ' SET ' . $_keys . ' WHERE ' . $where;
		return $this->query($sql, array_merge($data, $values));
	}
	
	public function delete($table, $where, $data = array())
	{
		$sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
		return $this->query($sql, $data);
	}
	
	public function fetchArray() { return $this->fetch(\PDO::FETCH_ASSOC); }
    public function fetchArrayAll() { return $this->fetchAll(\PDO::FETCH_ASSOC); }
    public function fetchObject() { return $this->fetch(\PDO::FETCH_OBJ); }
    public function fetchObjectAll() { return $this->fetchAll(\PDO::FETCH_OBJ); }
    public function rowCount() { return $this->stmt->rowCount(); }
    public function getLastInsertId(){ return $this->dbh->lastInsertId(); }
	
	/* Transacts */
	public function beginTransaction()
	{
		$this->isTransaction = true;
		$this->dbh->beginTransaction();
	}
	public function commit()
	{
		$this->isTransaction = false;
		$this->dbh->commit();
	}
	public function rollback()
	{
		$this->isTransaction = false;
		$this->dbh->rollback();
	}

	/* Privates */
	private function fetch($mode)
	{
		try
		{
			$this->stmt->setFetchMode($mode);
			return $this->stmt->fetch();
		}
		catch(\Exception $e) { $this->showError($e); }
	}
	
	private function fetchAll($mode)
	{
		try
		{
			$this->stmt->setFetchMode($mode);
			return $this->stmt->fetchAll();
		}
		catch(\Exception $e) { $this->showError($e); }
	}
	
	private function prepare($sql) { $this->stmt = $this->dbh->prepare($sql); }
	private function bindValues($data)
    {
        foreach($data as $key => $value)
        {
            switch(gettype($value))
            {
				case 'integer': $type = \PDO::PARAM_INT; $value = (integer)$value; break;
				case 'boolean': $type = \PDO::PARAM_BOOL; $value = (boolean)$value; break;
				case 'NULL': $type = \PDO::PARAM_NULL; break;
				default: $type = \PDO::PARAM_STR; $value = (string)$value; break;
            }
            $this->stmt->bindValue($key, $value, $type);
        }
    }
	
	private function showError(\Exception $e)
	{
		if($this->isTransaction && AUTO_ROLLBACK) { $this->isTransaction = false; $this->rollback(); }
		Error::showMessage($e->getMessage(), Message::$default, true);
	}
	
	public function __clone() { throw new Exception('No se puede clonar la clase DB'); }
}
