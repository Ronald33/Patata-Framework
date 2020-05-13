<?php
namespace core\rest;

require_once(PATH_BASE . '/core/uriDecoder/URIDecoder.php');
require_once(PATH_BASE . '/core/middleware/IMiddleware.php');

use core\middleware\IMiddleware;
use core\uriDecoder\URIDecoder;


class Middleware implements IMiddleware
{
    public function execute(URIDecoder $uriDecoder)
    {
        $uriDecoder->getRest()->auth();
    }
}