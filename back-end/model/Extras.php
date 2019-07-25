<?php
require_once(MODEL . 'extrasDAO/ExtrasMYSQL.php');

class Extras
{
	private $dao;
	
	public function __construct()
	{
		$this->dao = new ExtrasMYSQL();
	}
	
	public function getEnumValues($table, $field)
    {
        $_field = $this->dao->getField($table, $field);
        preg_match('/^enum\(\'(.*)\'\)$/', $_field['Type'], $matches);
        $enum = explode("','", $matches[1]);
		return $enum;
    }
}
