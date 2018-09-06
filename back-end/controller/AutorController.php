<?php
require_once(MODEL . 'AutorModel.php');
require_once(VIEW . 'ResponseView.php');
require_once(VALIDATOR . 'AutorValidator.php');
require_once(HELPER . 'Helper.php');

class AutorController
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$this->model = new AutorModel();
		$this->responseView = new ResponseView();
    }
    
    public function get($id = null)
    {
        if($id)
        {
            $result = $this->model::selectById($id);
            if($result == NULL) { $this->responseView->s404(); }
            else { $this->responseView->s200($result); }
        }
        else
        {
            $result = $this->model::selectAll();
            $this->responseView->s200($result);
        }
    }

    public function post()
    {
        $obj = Helper::getInputs();
        $response = AutorValidator::tryValidate($obj);

        if($response === true)
        {
            $this->model = Helper::cast('AutorModel', $obj);
            $this->responseView->s201(array('id' => $this->model->insert()));
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
                $this->model = Helper::cast('AutorModel', $obj);
                $this->responseView->s201($this->model->update());
            }
            else { $this->responseView->s400($response); }
        }
    }
    public function delete($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else
        {
            $this->model->setId($id);
            if($this->model->isUsedByLibrosAutores()) { $this->responseView->s409(); }
            else { $this->responseView->s200($this->model->delete()); }
        }
    }
}
