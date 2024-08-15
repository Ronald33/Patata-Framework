<?php
namespace core\middleware;

use core\uriDecoder\URIDecoder;

abstract class Middleware
{
    private $uriDecoder;

    public function setURIDecoder(URIDecoder $uriDecoder) { $this->uriDecoder = $uriDecoder; }
    protected function getURIDecoder() { return $this->uriDecoder; }

    abstract public function execute();
}