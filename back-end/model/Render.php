<?php

class Render
{
    public static function getBooks()
    {
        return array
        (
            array('name' => 'Book 1', 'pages' => '200', 'chapters' => array('C1', 'C2', 'C3')), 
            array('name' => 'Book 2', 'pages' => '50')
        );
    }
}