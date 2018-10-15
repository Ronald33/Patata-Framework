<?php
require_once(MODEL . 'Autor.php');

interface IAutorDAO
{
  	public function selectAll();
  	public function selectById($id);
  	public function insert(Autor $autor);
  	public function update(Autor $autor);
  	public function delete(Autor $autor);
    public function isUsedByLibrosAutores(Autor $autor);
    public function getFromLibro(Libro $libro);
    public function addToLibro(Autor $autor, Libro $libro, $db);
    public function deleteFromLibro(Libro $libro, $db);
}
