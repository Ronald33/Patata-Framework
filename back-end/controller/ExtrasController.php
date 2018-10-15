<?php
require_once(MODEL . 'Extras.php');
require_once(VIEW . 'ResponseView.php');
require_once(HELPER . 'Helper.php');

class ExtrasController
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$this->model = new Extras();
		$this->responseView = new ResponseView();
    }
    
    public function get($id = null)
    {
        if($id)
        {
            switch($id)
            {
                case 'libros_estado':
                    $this->responseView->s200($this->model->getEnumValues('libros', 'libr_estado'));
                break;
                default: 
                    $this->responseView->s404();
            }
        }
        else { $this->responseView->s404(); }
    }
}
