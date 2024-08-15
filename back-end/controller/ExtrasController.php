<?php
class ExtrasController
{
	private $dao;
	private $view;

	public function __construct()
	{
		$this->dao = new ExtrasDAO();
		$this->view = Repository::getResponse();
    }

    public function get($id = null)
    {
        switch($id)
        {
            case 'tipos-de-usuario':
                $this->view->j200($this->dao->getEnumValues('usuarios', 'usua_tipo'));
            break;
            default:
                $this->view->j404();
        }
    }

    public function options() { header('Access-Control-Allow-Methods: ' . Helper::getAllowedMethodsFromClass(__CLASS__)); }
}