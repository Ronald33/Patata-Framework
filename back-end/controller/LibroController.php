<?php
require_once(MODEL . 'LibroModel.php');
require_once(VIEW . 'ResponseView.php');
require_once(VALIDATOR . 'LibroValidator.php');
require_once(HELPER . 'Helper.php');

class LibroController
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$this->model = new LibroModel();
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
        $response = LibroValidator::tryValidate($obj);

        if($response === true)
        {
            $this->model = Helper::cast('LibroModel', $obj);
            $this->model->setEditorial(new EditorialModel($obj->editorial));
            $this->model->setAutores($obj->autores);
            $this->model->setFechaIngreso(Helper::convertDateToMysqlFormat($obj->fecha_ingreso));
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
            $response = LibroValidator::tryValidate($obj);
            
            if($response === true)
            {
                $this->model = Helper::cast('LibroModel', $obj);
                $this->model->setId($id);
                $this->model->setEditorial(new EditorialModel($obj->editorial));
                $this->model->setAutores($obj->autores);
                $this->model->setFechaIngreso(Helper::convertDateToMysqlFormat($obj->fecha_ingreso));
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
			$this->responseView->s200($this->model->delete());
		}
    }
}
