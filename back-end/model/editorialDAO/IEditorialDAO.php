<?php
require_once(MODEL . 'Editorial.php');

interface IEditorialDAO
{
  	public function selectAll();
  	public function selectById($id);
  	public function insert(Editorial $editorial);
  	public function update(Editorial $editorial);
  	public function delete(Editorial $editorial);
}