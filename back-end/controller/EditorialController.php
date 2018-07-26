<?php
require_once(MODEL . 'Editorial.php');
require_once(VIEW . 'ResponseView.php');
require_once(HELPER . 'EditorialHelper.php');
require_once(VALIDATOR . 'EditorialValidator.php');

class EditorialController
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$this->model = new Editorial();
		$this->responseView = new ResponseView();
    }
    
    public function post()
    {
        $nombre = 'Nueva Editorial';
        $data = array('nombre' => $nombre);
        $data_object = (object) $data;
        $response = EditorialValidator::tryValidate($data_object);

        if($response === true)
        {
            $editorial = EditorialHelper::getEditorialFromObject($data_object);
            $editorial->insert();
            $this->responseView->s201();
        }
        else { $this->responseView->s400($response); }
    }
    public function get($id = null)
    {
        $result = null;
        if($id) { $result = $this->model::selectById($id); }
        else { $result = $this->model::selectAll(); }
        $this->responseView->s200($result);
    }
    public function put($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else
        {
            $nombre = "Nombre modificado";
            $data = array
            (
                'id' => $id, 
                'nombre' => $nombre
            );
            $data_object = (object) $data;
            $response = EditorialValidator::tryValidate($data_object);
            
            if($response === true)
            {
                $editorial = EditorialHelper::getEditorialFromObject($data_object);
                $this->responseView->s201($editorial->update());
            }
            else { $this->responseView->s400($response); }
        }
    }
    public function delete($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else { $this->responseView->s200($this->model::delete($id)); }
    }
}