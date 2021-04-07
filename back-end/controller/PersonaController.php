<?php
class PersonaController
{
	private $dao;
	private $view;

	public function __construct()
	{
		$this->dao = new PersonaDAO();
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
        else if(isset($_GET['filter'])) { $this->view->s200($this->dao->selectFiltered($_GET['filter'])); }
        else { $this->view->s200($this->dao->selectAll()); }
    }

    public function post()
    {
        $object = Helper::getBodyRequest();
        $persona = PersonaHelper::castToPersona($object);
        $this->dao->insert($persona);
        $this->view->s201($persona);
    }

    public function put($id = null)
    {
        if($id == null) { $this->view->s501(); }
        else
        {
            $object = Helper::getBodyRequest();
            $persona = PersonaHelper::castToPersona($object);
            $persona->setId($id);
            $this->dao->update($persona);
            $this->view->s201($persona);
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
