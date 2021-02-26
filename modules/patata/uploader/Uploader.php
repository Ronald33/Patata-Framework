<?php
namespace modules\patata\uploader;

require_once(__DIR__ . '/Helper.php');
require_once(__DIR__ . '/Message.php');

require_once(PATH_BASE . '/core/IError.php');
use \core\IError;

class Uploader
{
    private $key;
    private $maxSize;
    private $allowedTypes;
    private $datetime;
    private $error;
    
    private $config;
    private static $path_config = __DIR__ . '/config.ini';

    public function __construct(IError $error, $key)
    {
        $this->error = $error;
        $this->key = $key;
        $this->config = parse_ini_file(self::$path_config);
        $this->allowedTypes = [];
        $this->setMaxSize($this->config['MAX_SIZE']);
        $this->setDatetime(Helper::getCurrentTimestamp());
    }

    public function setDatetime($timestamp)
    {
        $this->datetime = new \DateTime('', new \DateTimeZone($this->config['ZONA_HORARIA']));
        $this->datetime->setTimestamp($timestamp);
    }

    public function setMaxSize($maxSize)
    {
        $maxSize *= 1024*1024;
        if($maxSize > self::getUploadMaxFilesize())
        {
            $this->error->showMessage(Message::overflow($maxSize, self::getUploadMaxFilesize()), Message::$byDefault);
        }
        else { $this->maxSize = $maxSize; }
    }

    public function addAllowedType($allowedType)
    {
        array_push($this->allowedTypes, $allowedType);
    }

    public function getMaxSize() { return $this->maxSize; }
    public function getAllowedTypes() { return $this->allowedTypes; }

    public function execute()
    {
        $fileName = $_FILES[$this->key]['name'];
        $parts = explode(".", $fileName);
        $fileExtension = strtolower(end($parts));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $path = $this->getFolder() . '/' . $newFileName;

        if(!@move_uploaded_file($_FILES[$this->key]['tmp_name'], $path))
        {
            $this->error->showMessage(Message::error($this->key), Message::$byDefault);
        }
        return $path;
    }

    private function getFolder()
    {
        $mode = 0775;
        $folder = $this->config['PATH_UPLOAD'];
        if(!is_dir($folder)) { mkdir($folder, $mode); }
        $year = $this->datetime->format('Y');
        $folder .= '/' . $year;
        if(!is_dir($folder)) { mkdir($folder, $mode); }
        $month = $this->datetime->format('m');
        $folder .= '/' . $month;
        if(!is_dir($folder)) { mkdir($folder, $mode); }
        return $folder;
    }

    private static function getUploadMaxFilesize()
    {
        $conf = trim(ini_get('upload_max_filesize'));
        $val = substr($conf, 0, -1);
        $mod = strtolower(substr($conf, -1));
        switch($mod)
        {
            // El modificador 'G' está disponble desde PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}