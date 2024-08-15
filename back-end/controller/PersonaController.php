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
            if($result) { $this->view->j200($result); }
            else { $this->view->j404(); }
        }
        else if(isset($_GET['wildcard'])) { $this->view->j200($this->dao->selectByWildcard($_GET['wildcard'])); }
        else { $this->view->j200($this->dao->selectAll()); }
    }

    public function post()
    {
        $payload = Helper::getPayload();
        $result = PersonaValidator::validate($payload);
        if($result !== true) { $this->view->j400($result); }
        $persona = Helper::cast('Persona', $payload);
        $this->dao->insert($persona);
        $this->view->j201($persona);
    }

    public function put($id = null)
    {
        if($id == null) { $this->view->j501(); }
        
        $payload = Helper::getPayload();
        $result = PersonaValidator::validate($payload, $id);
        if($result !== true) { $this->view->j400($result); }
        $persona = Helper::cast('Persona', $payload);
        $persona->setId($id);
        $this->dao->update($persona);
        $this->view->j201($persona);
    }
    
    public function delete($id = null)
    {
        if($id == null) { $this->view->j501(); }
        $this->dao->delete($id); $this->view->j200();
    }

    public function options() { header('Access-Control-Allow-Methods: ' . Helper::getAllowedMethodsFromClass(__CLASS__)); }
}
