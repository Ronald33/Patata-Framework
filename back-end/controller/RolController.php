<?php
require_once(MODEL . 'rolDAO/RolMYSQL.php');
require_once(HELPER . 'Helper.php');
use REST\REST;
use REST\Response;

class RolController
{
    private $dao;
    private $view;

    public function __construct()
    {
        $this->dao = new RolMYSQL();
    }

    public function get()
    {
		$result = $this->dao->getAll();
		Response::s200($result);
    }

    public function options()
    {
        header('Access-Control-Allow-Methods: ' . REST::getAllowedMethodsFromClass(__CLASS__));
    }
}
