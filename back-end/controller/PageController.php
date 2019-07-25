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
		$data = array('message' => $message);
		$this->view->index($data);
	}

	public function s404()
	{
		$data = array();
		$this->view->s404($data);
	}
}
