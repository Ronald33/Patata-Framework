<?php
namespace REST;
abstract class Token
{
	private static $headers;
	private static $pwd;
	private static $iv16;
	private static $encrypt_method;
	private static $data;

	private static function init()
	{
		self::$pwd = md5('ecdqemsd');
		self::$iv16 = substr(md5('hotel'), 0, 16);
		self::$encrypt_method = 'AES-256-CBC';
	}

	public static function getToken($data)
	{
		self::init();
		return base64_encode(openssl_encrypt(serialize($data), self::$encrypt_method, self::$pwd, 0, self::$iv16));
	}

	public static function getDataFromToken($token)
	{
		self::init();
		return unserialize(openssl_decrypt(base64_decode($token), self::$encrypt_method, self::$pwd, 0, self::$iv16));
	}
}
