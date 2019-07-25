<?php
require_once(MODEL . 'renderDAO/Render.php');

class RenderController
{
	private $model;
    private $view;

    public function __construct()
	{
		$this->model = new Render();
		$this->view = new RenderView();
    }

    public function index()
    {
        $books = $this->model->getAll();
        $variable = 5 + 5;
        $data = array
        (
            'variable' => $variable,
            'open_tags' => '{{',
            'close_tags' => '}}',
            'books' => $books
        );
        $this->view->render($data);
    }
}
