<?php
class PageController
{
	private $model;
	private $view;

	public function __construct()
	{
		$this->model = new Page();
		$this->view = new PageView();
	}

	public function index()
	{
		$message = $this->model->getMessage();
		$data = ['message' => $message];
		$this->view->index($data);
	}

	public function s404()
	{
		$data = ['TITLE' => 'Página no encontrada'];
		http_response_code(404);
		$this->view->s404($data);
	}
}