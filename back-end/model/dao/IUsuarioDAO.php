<?php

interface IUsuarioDAO
{
	public function selectAll();
	public function selectById($id);
	public function selectByUserAndPassword($user, $password);
	public function insert(Usuario $usuario);
	public function update(Usuario $usuario);
	public function delete($id);
	public function setHabilitado($id, $habilitado);
}
