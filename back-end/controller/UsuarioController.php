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
            $result = $this->dao->selectById($id);
            if($result == NULL) { $this->view->s404(); }
            else { $this->view->s200($result); }
        }
        else if(isset($_GET['usuario']) && isset($_GET['contrasenha']))
		{
			$result = $this->dao->selectByUserAndPassword($_GET['usuario'], $_GET['contrasenha']);
			if($result == NULL) { $this->view->s404(); }
            else
            {
                $uriDecoder = Repository::getURIDecoder();
                $rest = $uriDecoder->getRest();
                if($rest) { $result->token = $rest->getToken()->encode($result); }
                $this->view->s200($result);
            }
		}
        else { $result = $this->dao->selectAll(); $this->view->s200($result); }
    }

    public function post()
    {
        $object = Helper::getBodyRequest();
        $usuario = UsuarioHelper::castToUsuario($object);
        $this->dao->insert($usuario);
        $this->view->s201($usuario);
    }

    public function put($id = null)
    {
        if($id == null) { $this->view->s501(); }
        else
        {
            $object = Helper::getBodyRequest();
            if(is_object($object)) { $object->id = $id; }
            $usuario = UsuarioHelper::castToUsuario($object);
            $this->dao->update($usuario);
            $this->view->s201($usuario);
        }
    }

    public function patch($id = null)
    {
		if($id == NULL) { $this->view->s501(); }
		else
		{
			$object = Helper::getBodyRequest();
            if($object)
            {
                if(isset($object->habilitado)) { $this->dao->setHabilitado($id, $object->habilitado); }
                $this->view->s204();
            }
			else { $this->view->s501(); }
		}
    }
    
    public function delete($id = null)
    {
        if($id == null) { $this->view->s501(); }
        else { $this->dao->delete($id); $this->view->s200(); }
    }

    public function options()
    {
        $rest = Repository::getRest();
        header('Access-Control-Allow-Methods: ' . $rest->getAllowedMethodsFromClass(__CLASS__));
    }
}
