<?php
class Render implements IRenderDAO
{
    public function getAll()
    {
        return [
			['name' => 'Book 1', 'pages' => '200', 'chapters' => ['C1', 'C2', 'C3']], 
            ['name' => 'Book 2', 'pages' => '50']
        ];
  	}
}
