<?php
namespace core\rest\token\myjwt;

require_once(PATH_CORE . '/rest/token/IToken.php');

class PatataJWT implements \core\rest\token\IToken
{
    private $config;
    private $key;
    private $alg;

    public function __construct($extra_config_path = NULL)
    {
        $extra_config = $extra_config_path !== NULL ? parse_ini_file($extra_config_path) : [];
        $this->config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'), $extra_config);

        assert(is_string($this->config['KEY']), 'In MyJWT, KEY is invalid');
        assert($this->config['ALG'] === 'HS256', 'In MyJWT, only HS256 is supported');

        $this->key = $this->config['KEY'];
        $this->alg = $this->config['ALG'];
    }

    public function encode($payload)
    {
        $header = ['alg' => $this->alg, 'type' => 'JWT'];

        $segments = [
            $this->base64UrlEncode(json_encode($header)),
            $this->base64UrlEncode(json_encode($payload))
        ];

        $signature = hash_hmac('sha256', implode('.', $segments), $this->key, true);
        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

	public function decode($token)
    {
        $parts = explode('.', $token);
        if(count($parts) !== 3) { return null; }

        [$headerB64, $payloadB64, $signatureB64] = $parts;

        $header = json_decode($this->base64UrlDecode($headerB64), true);
        $payload = json_decode($this->base64UrlDecode($payloadB64), true);
        $signature = self::base64UrlDecode($signatureB64);

        if(!$header || !$payload || !$signature) return null;

        $validSig = hash_hmac('sha256', "$headerB64.$payloadB64", $this->key, true);
        if (!hash_equals($validSig, $signature)) return null;

        return $payload;
    }

    private function base64UrlEncode(string $data) { return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); }

    private function base64UrlDecode(string $data)
    {
        $pad = strlen($data) % 4;
        if ($pad > 0) $data .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($data, '-_', '+/'));
    }
}