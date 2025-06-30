<?php
class PageController
{
	private $model;
	private $view;

	public function __construct()
	{
		$this->model = new PageDAO();
		$this->view = new PageView();
	}

	public function index()
	{
		$this->view->index($this->model->select());
	}

	public function s404()
	{
		$data = ['TITLE' => 'Página no encontrada'];
		http_response_code(404);
		$this->view->s404($data);
	}
}