<?php
abstract class Repository
{
    private static $uploader_docs;

    public static function getError($show_errors = NULL)
    {
        require_once('modules/patata/error/Error.php');
        $error = new modules\patata\error\Error($show_errors);
        return $error;
    }

    public static function getToken()
    {
        require_once('modules/patata/token/Token.php');
        $token = new modules\patata\token\Token();
        return $token;
    }

    public static function getREST()
    {
        require_once('core/rest/REST.php');
        $rest = core\rest\REST::getInstance();
        $rest->setError(self::getError());
        $rest->setToken(self::getToken());
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
		$middleware->setError(self::getError());
        return $middleware;
    }

    public static function getCaller($path)
    {
        require_once('core/caller/Caller.php');
        $caller = core\caller\Caller::getInstance($path);
        return $caller;
    }

    public static function getResponse()
    {
        require_once('core/response/Response.php');
        $response = core\response\Response::getInstance();
        return $response;
    }

    public static function getRender($data = [])
    {
        require_once('modules/patata/render/Render.php');
        $render = new modules\patata\render\Render($data);
        $render->setError(self::getError());
        return $render;
    }

    public static function getDB()
    {
        require_once('modules/patata/db/DB.php');
        $db = modules\patata\db\DB::getInstance(self::getError());
        return $db;
    }

    public static function getValidator()
    {
        require_once('modules/patata/validator/Validator.php');
        $validator = new modules\patata\validator\Validator();
        $validator->setError(self::getError());
        return $validator;
    }

    public static function getUploader($key)
    {
        require_once('modules/patata/uploader/Uploader.php');
        $uploader = new modules\patata\uploader\Uploader(self::getError(false), $key);
        return $uploader;
    }

    public static function getUploaderDocs($key)
    {
        if(isset(self::$uploader_docs)) { return self::$uploader_docs; }

        self::$uploader_docs = self::getUploader($key);
        self::$uploader_docs->addAllowedType('application/pdf');
        return self::$uploader_docs;
    }
}
