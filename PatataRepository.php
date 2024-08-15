<?php
abstract class PatataRepository
{
    public static function getURIDecoder($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        if(ENABLE_REST)
        {
            require_once(PATH_CORE . '/uriDecoder/rest/RESTURIDecoder.php');
            $uriDecoder = core\uriDecoder\rest\RESTURIDecoder::getInstance($extra_configuration_path);
        }
        else
        {
            require_once(PATH_CORE . '/uriDecoder/classical/ClassicalURIDecoder.php');
            $uriDecoder = core\uriDecoder\classical\ClassicalURIDecoder::getInstance($extra_configuration_path);
        }
        return $uriDecoder;
    }

    public static function getMiddlewareExecutor()
    {
        require_once(PATH_CORE . '/middleware/MiddlewareExecutor.php');
        return core\middleware\MiddlewareExecutor::getInstance();
    }

    public static function getToken($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_CORE . '/rest/token/myjwt/MyJWT.php');
        return new core\rest\token\myjwt\MyJWT($extra_configuration_path);
    }

    public static function getREST($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_CORE . '/rest/REST.php');
        $rest = core\rest\REST::getInstance($extra_configuration_path);
        $rest->setToken(self::getToken($extra_configuration_path));
        return $rest;
    }

    public static function getRESTMiddleware($uriDecoder, $extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_CORE . '/rest/RESTMiddleware.php');
        $restMiddleware = new core\rest\RESTMiddleware(PatataRepository::getREST($extra_configuration_path));
        $restMiddleware->setURIDecoder($uriDecoder);
        return $restMiddleware;
    }

    public static function getCaller($path = PATH_CONTROLLER, $extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_CORE . '/caller/Caller.php');
        return core\caller\Caller::getInstance($path, $extra_configuration_path);
    }

    public static function getRender($includeHeaderAndFooter = true, $extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_MODULES . '/patata/render/Render.php');

        if($includeHeaderAndFooter)
        {
            return new modules\patata\render\Render($extra_configuration_path, PATH_HTML . DIRECTORY_SEPARATOR . 'header.phtml', PATH_HTML . DIRECTORY_SEPARATOR . 'footer.phtml');
        }
        else { return new modules\patata\render\Render($extra_configuration_path); }
        
        return $render;
    }

    public static function getResponse($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once('core/response/Response.php');
        return core\response\Response::getInstance($extra_configuration_path);
    }

    public static function getDB($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once('modules/patata/db/DB.php');
        $db = modules\patata\db\DB::getInstance($extra_configuration_path);
        return $db;
    }

    public static function getValidator()
    {
        require_once('modules/patata/validator/Validator.php');
        $validator = new modules\patata\validator\Validator();
        $validator->addSource(__DIR__ . '/for-custom/MyRule.php', 'MyRule');
        return $validator;
    }
}
