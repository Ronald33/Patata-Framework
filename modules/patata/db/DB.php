<?php
namespace patata\db;

class DB
{
	private $dbh;
	private $stmt;
	private $isTransaction;
	private static $instance;

	private $config;
	
	private function __construct($extra_configuration_path)
	{
		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path, true) : [];
		$this->config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true), $extra_config);

		$this->checkConfigAsserts();

		$environment = $this->config[$this->config['ENVIRONMENT']];

		$dsn = 'mysql:dbname=' . $environment['DB_NAME'] . ';host=' . $environment['HOST'];
		$options = 
		[
			\PDO::ATTR_PERSISTENT => true, 
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, 
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = "' . $this->config['TIME_ZONE'] . '"; SET NAMES ' . $this->config['DB_CHARSET']
		];
		$this->dbh = new \PDO($dsn, $environment['USER'], $environment['PASSWORD'], $options);

	}

	private function checkConfigAsserts()
	{
		$environments = ['DEVELOPMENT', 'PRODUCTION'];
		$booleans = ['1', ''];

		assert(in_array($this->config['AUTO_ROLLBACK'], $booleans), 'In DB, AUTO_ROLLBACK must be a bool');
		assert(in_array($this->config['ENVIRONMENT'], $environments), 'In DB, ENVIRONMENT is invalid');
		assert(is_string($this->config['DB_CHARSET']), 'In DB, DB_CHARSET is invalid');
		assert(is_string($this->config['TIME_ZONE']), 'In DB, TIMEZONE is invalid');
		assert(in_array($this->config['FETCH_OBJECT'], $booleans), 'In DB, FETCH_OBJECT must be a bool');

		$environment = $this->config['ENVIRONMENT'];
		assert(is_string($this->config[$environment]['HOST']), 'In DB, HOST (' . $environment . ') is invalid');
		assert(is_string($this->config[$environment]['USER']), 'In DB, HOST (' . $environment . ') is invalid');
		assert(is_string($this->config[$environment]['PASSWORD']), 'In DB, PASSWORD (' . $environment . ') is invalid');
		assert(is_string($this->config[$environment]['DB_NAME']), 'In DB, DB_NAME (' . $environment . ') is invalid');
	}
	
	public static function getInstance($extra_configuration_path = NULL)
	{
		if(self::$instance == NULL) { self::$instance = new DB($extra_configuration_path); }
		return self::$instance;
	}
	
	public function query($sql, $data = [])
	{
		try
		{
			$this->prepare($sql);
			$this->bindValues($data);
			return $this->stmt->execute();
		}
		catch(\Exception $e)
		{
			if($this->isTransaction && $this->config['AUTO_ROLLBACK']) { $this->rollback(); }
			throw $e;
		}
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

	public function rowCount() { return $this->stmt->rowCount(); }
    public function getLastInsertId(){ return $this->dbh->lastInsertId(); }
	
	public function insert($table, $data)
	{
		$fields = array_keys($data);

        $sql = 'INSERT INTO ' . $table . 
                '(' . implode(', ', $fields) . ') ' . 
                'VALUES ' . 
                '(:' . implode(', :', $fields) . ')';

        return $this->query($sql, $data);
	}

	public function update($table, $replacements, $where, $data = [])
	{
		$keys = [];
		$values = [];
		foreach($replacements as $key => $value)
		{
			array_push($keys, $key . ' = :' . $key);
			$values[$key] = $value;
		}
		$_keys = implode(', ', $keys);

		$data_new = [];
		$unique = uniqid();

		foreach($data as $key => $value) { $data_new[$key . '_' . $unique] = $value; }

		$fn_add_two_points = function($value){ return ':' . $value; };

		$where = str_replace(array_map($fn_add_two_points, array_keys($data)), array_map($fn_add_two_points, array_keys($data_new)), $where);
	
		$sql = 'UPDATE ' . $table . ' SET ' . $_keys . ' WHERE ' . $where;

		return $this->query($sql, array_merge($data_new, $values));
	}
	
	public function delete($table, $where, $data = [])
	{
		$sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
		return $this->query($sql, $data);
	}

	public function select($table, $fields, $where = '', $data = [], $extra = '')
	{
		$this->_select($table, $fields, $where, $data, $extra);
		return $this->fetchAll($this->config['FETCH_OBJECT'] ? \PDO::FETCH_OBJ : \PDO::FETCH_ASSOC);
	}

	public function selectOne($table, $fields, $where = '', $data = [], $extra = '')
	{
		$this->_select($table, $fields, $where, $data, $extra);
		return $this->fetch($this->config['FETCH_OBJECT'] ? \PDO::FETCH_ASSOC : \PDO::FETCH_OBJ);
	}
	
	private function _select($table, $fields, $where, $data, $extra)
	{
		$a_fields = [];
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
		$sql = 'SELECT ' . $fields . ' FROM ' . $table . $where . ' ' . $extra;		$this->query($sql, $data);
	}
	
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

	public function inTransaction() { return $this->dbh->inTransaction(); }

	private function _setFetchMode()
	{
		if(isset($this->config['FETCH_OBJECT']) && $this->config['FETCH_OBJECT']) { $this->stmt->setFetchMode(\PDO::FETCH_OBJ); }
		else { $this->stmt->setFetchMode(\PDO::FETCH_ASSOC); }
	}

	public function fetch()
	{
		$this->_setFetchMode();
		return $this->stmt->fetch();
	}
	
	public function fetchAll()
	{
		$this->_setFetchMode();
		return $this->stmt->fetchAll();
	}

	public function fetchArray() { $this->stmt->setFetchMode(\PDO::FETCH_ASSOC); return $this->stmt->fetch(); }
    public function fetchArrayAll() { $this->stmt->setFetchMode(\PDO::FETCH_ASSOC); return $this->stmt->fetchAll(); }
    public function fetchObject() { $this->stmt->setFetchMode(\PDO::FETCH_OBJ); return $this->stmt->fetch(); }
    public function fetchObjectAll() { $this->stmt->setFetchMode(\PDO::FETCH_OBJ); return $this->stmt->fetchAll(); }
	
	public function __clone() { throw new \Exception('No se puede clonar la clase ' . __CLASS__); }
}
