<?php
abstract class Repository
{
    public static function getREST()
    {
        require_once('modules/patata/error/Error.php');
        require_once('modules/patata/token/Token.php');
        require_once('core/rest/REST.php');

        $error = new modules\patata\error\Error();
        $token = new modules\patata\token\Token();
        $rest = core\rest\REST::getInstance();
        $rest->setError($error);
        $rest->setToken($token);
        return $rest;
    }

    public static function getURIDecoder()
    {
        require_once('core/uriDecoder/URIDecoder.php');
        $uriDecoder = core\uriDecoder\URIDecoder::getInstance();
        return $uriDecoder;
    }

    public static function getMiddleware()
    {
        require_once('core/middleware/Middleware.php');
        $middleware = core\middleware\Middleware::getInstance();
        return $middleware;
    }

    public static function getCaller($path)
    {
        require_once('core/caller/Caller.php');
        $caller = core\caller\Caller::getInstance($path);
        return $caller;
    }

    public static function getRender($data = [])
    {
        require_once('modules/patata/render/Render.php');
        require_once('modules/patata/error/Error.php');
        $render = new modules\patata\render\Render($data);
        $error = new modules\patata\error\Error();
        $render->setError($error);
        return $render;
    }
}
