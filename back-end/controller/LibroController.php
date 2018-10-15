<?php
require_once(MODEL . 'libroDAO/LibroMYSQL.php');
require_once(VIEW . 'ResponseView.php');
require_once(VALIDATOR . 'LibroValidator.php');
require_once(HELPER . 'Helper.php');

class LibroController
{
	private $dao;
	private $view;

	public function __construct()
	{
		$this->dao = new LibroMYSQL();
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
        $response = LibroValidator::tryValidate($obj);

        if($response === true)
        {
            $libro = Helper::cast('Libro', $obj);
            $libro->setEditorial(new Editorial($obj->editorial));
            $libro->setAutores($obj->autores);
            $libro->setFechaIngreso(Helper::convertDateToMysqlFormat($obj->fecha_ingreso));
            $this->responseView->s201(array('id' => $this->dao->insert($libro)));
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
                $libro = Helper::cast('Libro', $obj);
                $libro->setId($id);
                $libro->setEditorial(new Editorial($obj->editorial));
                $libro->setAutores($obj->autores);
                $libro->setFechaIngreso(Helper::convertDateToMysqlFormat($obj->fecha_ingreso));
                $this->responseView->s201($this->dao->update($libro));
            }
            else { $this->responseView->s400($response); }
        }
    }
    public function delete($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else
        {
            $libro = new Libro($id);
			$this->responseView->s200($this->dao->delete($libro));
		}
    }
}
