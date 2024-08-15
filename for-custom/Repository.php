<?php
require_once(PATH_BASE . DIRECTORY_SEPARATOR . 'PatataRepository.php');
abstract class Repository extends PatataRepository
{
    // Here you can add custom methods that instantiate objects
    /*public static function getMyRender($includeHeaderAndFooter = true)
    {
        require_once(PATH_MODULES . '/patata/render/Render.php');

        $extra_configuration_path = PATH_BASE . DIRECTORY_SEPARATOR . 'for-custom' . DIRECTORY_SEPARATOR . 'config-custom.ini';
        if($includeHeaderAndFooter)
        {
            return new modules\patata\render\Render($extra_configuration_path, PATH_HTML . DIRECTORY_SEPARATOR . 'header.phtml', PATH_HTML . DIRECTORY_SEPARATOR . 'footer.phtml');
        }
        else { return new modules\patata\render\Render($extra_configuration_path); }
        
        return $render;
    }*/

    public static function getMyMiddleware()
    {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . 'MyMiddleware.php');
        $middleware = new MyMiddleware();
        $middleware->setURIDecoder(self::getURIDecoder());
        return $middleware;
    }
}