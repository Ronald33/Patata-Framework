<?php
class PageRestController
{
	private $model;
	private $view;

	public function __construct()
	{
		$this->model = new PageDAO();
		$this->view = Repository::getResponse();
	}

	public function get()
	{
		$this->view->j200($this->model->select());
	}
}