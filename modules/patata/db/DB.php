<?php
namespace modules\patata\db;

require_once(__DIR__ . '/Message.php');

require_once(PATH_BASE . '/core/IError.php');

use \Core\IError;

class DB
{
	private $db;
	private $dbh;
	private $sql;
	private $stmt;
	private $isTransaction;
	private $error;
	private static $instance;

	private $config;
    private static $path_config = __DIR__ . '/config.ini';
	
	private function __construct(IError $error)
	{
		$this->error = $error;
		try
		{
			$this->config = parse_ini_file(self::$path_config, true);
			$source = $this->config[$this->config['ENVIRONMENT']];

			$dsn = 'mysql:dbname=' . $source['DB_NAME'] . ';host=' . $source['HOST'] . ';charset=utf8';
			$options = array
			(
				\PDO::ATTR_PERSISTENT => true, 
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION/*, 
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"*/
			);
			$this->dbh = new \PDO($dsn, $source['USER'], $source['PASSWORD'], $options);
		}
		catch(\Exception $e) { $this->showError($e); }
	}
	
	public static function getInstance(IError $error)
	{
		if(self::$instance == NULL) { self::$instance = new DB($error); }
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
	
	private function _select($table, $fields, $where, $data, $limit)
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
	}

	public function select($table, $fields, $where = '', $data = array(), $limit = array())
	{
		$this->_select($table, $fields, $where, $data, $limit);
		return $this->fetchObjectAll();
	}

	public function selectOne($table, $fields, $where = '', $data = array(), $limit = array())
	{
		$this->_select($table, $fields, $where, $data, $limit);
		return $this->fetchObject();
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
		if($this->isTransaction && $this->config['AUTO_ROLLBACK']) { $this->isTransaction = false; $this->rollback(); }
		$this->error->showMessage($e->getMessage(), Message::$default);
	}
	
	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
