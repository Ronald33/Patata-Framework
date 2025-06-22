<?php
namespace core\rest\token\myjwt;

require_once(PATH_CORE . '/rest/token/IToken.php');
require_once(__DIR__ . '/vendor/autoload.php');

use core\rest\token\IToken;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class MyJWT implements IToken
{
    private $_config;
    private $_key;
    private $_alg;

    public function __construct($extra_config_path = NULL)
    {
        $extra_config = $extra_config_path !== NULL ? parse_ini_file($extra_config_path) : [];
        $this->_config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'), $extra_config);

        assert(is_string($this->_config['KEY']), 'In MyJWT, KEY is invalid');
        assert(is_string($this->_config['ALG']), 'In MyJWT, ALG is invalid');

        $this->_key = $this->_config['KEY'];
        $this->_alg = $this->_config['ALG'];
    }

    public function encode($payload) { return JWT::encode($payload, $this->_key, $this->_alg); }
	public function decode($token) { return (array) JWT::decode($token, new Key($this->_key, $this->_alg)); }
}