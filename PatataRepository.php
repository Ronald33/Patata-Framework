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
        require_once(PATH_FOR_CUSTOM . DIRECTORY_SEPARATOR . 'MyMiddlewareClassic.php');
        require_once(PATH_FOR_CUSTOM . DIRECTORY_SEPARATOR . 'MyMiddlewareRest.php');

        $middlewareExecutor = new core\middleware\MiddlewareExecutor();
        $middlewareExecutor->addMiddlewareClassic(new MyMiddlewareClassic());
        $middlewareExecutor->addMiddlewareRest(new MyMiddlewareRest());

        return $middlewareExecutor;
    }

    public static function getREST($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_CORE . '/rest/REST.php');
        require_once(PATH_CORE . '/rest/token/myjwt/PatataJWT.php');
        
        $rest = core\rest\REST::getInstance($extra_configuration_path);
        $rest->setToken(new \core\rest\token\myjwt\PatataJWT($extra_configuration_path));
        return $rest;
    }

    public static function getCaller($path = PATH_CONTROLLER, $extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_CORE . '/caller/Caller.php');
        return core\caller\Caller::getInstance($path, $extra_configuration_path);
    }

    public static function getResponse($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once('core/response/Response.php');
        return core\response\Response::getInstance($extra_configuration_path);
    }

    public static function getDB($extra_configuration_path = CUSTOM_CONFIG_PATH)
    {
        require_once(PATH_MODULES_PATATA . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'DB.php');
        $db = patata\db\DB::getInstance($extra_configuration_path);
        return $db;
    }

    public static function getValidator()
    {
        require_once(PATH_MODULES_PATATA . DIRECTORY_SEPARATOR . 'validator' . DIRECTORY_SEPARATOR . 'Validator.php');
        $validator = new patata\validator\Validator();
        $validator->addSource(__DIR__ . '/for-custom/MyRule.php', 'MyRule');
        return $validator;
    }
}
