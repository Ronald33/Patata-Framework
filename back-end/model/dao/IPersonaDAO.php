<?php

interface IPersonaDAO
{
	public function selectAll();
	public function selectById($id);
	public function selectFiltered($filter);
	public function insert(Persona $persona);
	public function update(Persona $persona);
	public function delete($id);
}
