<?php
require_once(MODEL . 'Libro.php');

interface ILibroDAO
{
  	public function selectAll();
  	public function selectById($id);
  	public function insert(Libro $libro);
  	public function update(Libro $libro);
  	public function delete(Libro $libro);
  	public function addAutores(Libro $libro);
  	public function deleteAutores(Libro $libro);
}
