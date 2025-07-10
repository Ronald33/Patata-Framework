<?php
require_once(PATH_CORE . '/rest/token/IToken.php');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class MyJWT implements \core\rest\token\IToken
{
    private $config;
    private $key;
    private $alg;

    public function __construct($config_path = NULL)
    {
        $this->config = parse_ini_file($config_path);

        assert(is_string($this->config['KEY']), 'In MyJWT, KEY is invalid');
        assert(is_string($this->config['ALG']), 'In MyJWT, ALG is invalid');

        $this->key = $this->config['KEY'];
        $this->alg = $this->config['ALG'];
    }

    public function encode($payload) { return JWT::encode($payload, $this->key, $this->alg); }
	public function decode($token) { return (array) JWT::decode($token, new Key($this->key, $this->alg)); }
}