<?php
require_once(MODEL . 'Render.php');
require_once(VIEW . 'RenderView.php');

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
        $books = $this->model::getBooks();
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