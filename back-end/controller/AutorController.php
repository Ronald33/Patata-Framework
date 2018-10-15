<?php
require_once(MODEL . 'autorDAO/AutorMYSQL.php');
require_once(VIEW . 'ResponseView.php');
require_once(VALIDATOR . 'AutorValidator.php');
require_once(HELPER . 'Helper.php');

class AutorController
{
	private $dao;
	private $view;

	public function __construct()
	{
		$this->dao = new AutorMYSQL();
		$this->responseView = new ResponseView();
    }

    public function get($id = null)
    {
        if($id)
        {
            $result = $this->dao->selectById($id);
            if($result == NULL) { $this->responseView->s404(); }
            else { $this->responseView->s200($result); }
        }
        else
        {
            $result = $this->dao->selectAll();
            $this->responseView->s200($result);
        }
    }

    public function post()
    {
        $obj = Helper::getInputs();
        $response = AutorValidator::tryValidate($obj);

        if($response === true)
        {
            $autor = Helper::cast('Autor', $obj);
            $this->responseView->s201(array('id' => $this->dao->insert($autor)));
        }
        else { $this->responseView->s400($response); }
    }

    public function put($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else
        {
            $obj = Helper::getInputs();
            $response = AutorValidator::tryValidate($obj);

            if($response === true)
            {
                $autor = Helper::cast('Autor', $obj);
                $autor->setId($id);
                $this->responseView->s201($this->dao->update($autor));
            }
            else { $this->responseView->s400($response); }
        }
    }
    public function delete($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else
        {
            $autor = new Autor($id);
            if($this->dao->isUsedByLibrosAutores($autor)) { $this->responseView->s409(); }
            else { $this->responseView->s200($this->dao->delete($autor)); }
        }
    }
}
