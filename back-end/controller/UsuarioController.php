<?php
class UsuarioController
{
	private $dao;
	private $view;

	public function __construct()
	{
		$this->dao = new UsuarioDAO();
		$this->view = Repository::getResponse();
    }

    public function get($id = null)
    {
        if($id)
        {
            $result = $this->dao->selectById($id, false);
            if($result) { $this->view->j200($result); }
            else { $this->view->j404(); }
        }
        else if(isset($_GET['user']) && isset($_GET['password']))
		{
			$result = $this->dao->selectByUserAndPassword($_GET['user'], $_GET['password'], false);
            if($result) { $this->view->j200(['user' => $result, 'token' => Helper::getTokenFromObject($result)]); }
            else { $this->view->j401(); }
		}
        else { $result = $this->dao->selectAll(); $this->view->j200($result); }
    }

    public function post()
    {
        $payload = Helper::getPayload();
        $result = UsuarioValidator::validate($payload);
        if($result !== true) { $this->view->j400($result); }
        $usuario = UsuarioHelper::castToUsuario($payload);
        $this->dao->insert($usuario);
        $this->view->j201($usuario);
    }

    public function put($id = null)
    {
        if($id == null) { $this->view->j501(); }

        $payload = Helper::getPayload();
        $result = UsuarioValidator::validate($payload, $id);
        if($result !== true) { $this->view->j400($result); }
        $usuario = UsuarioHelper::castToUsuario($payload);
        $usuario->setId($id);
        $this->dao->update($usuario);
        $this->view->j200($usuario);
    }

    public function patch($id = null)
    {
		if($id == NULL) { $this->view->j501(); }

        $payload = Helper::getPayload();
        if($payload)
        {
            if(isset($payload->habilitado)) { $this->dao->setHabilitado($id, $payload->habilitado); }
            $this->view->j204();
        }
        else { $this->view->j501(); }
    }
    
    public function delete($id = null)
    {
        if($id == null) { $this->view->j501(); }
        $this->dao->delete($id); $this->view->j204();
    }

    public function options() { header('Access-Control-Allow-Methods: ' . Helper::getAllowedMethodsFromClass(__CLASS__)); }
}