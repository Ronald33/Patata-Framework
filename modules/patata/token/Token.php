<?php
namespace modules\patata\token;

require_once(PATH_BASE . '/core/IToken.php');

use core\IToken;

class Token implements IToken
{
	private $pwd;
	private $iv16;
	private $encrypt_method;
	private $payload;

	public function __construct()
	{
		$this->pwd = md5('patata');
		$this->iv16 = substr(md5('framework'), 0, 16);
		$this->encrypt_method = 'AES-256-CBC';
	}

	public function encode($payload)
	{
		return base64_encode(openssl_encrypt(serialize($payload), $this->encrypt_method, $this->pwd, 0, $this->iv16));
	}

	public function decode($token)
	{
		return unserialize(openssl_decrypt(base64_decode($token), $this->encrypt_method, $this->pwd, 0, $this->iv16));
	}
}
