<?php
require_once(MODEL . 'Rol.php');

interface IRolDAO
{
  	public function getAll();
  	public function getById($id);
}
