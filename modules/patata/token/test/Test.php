<?php
define('PATH_BASE', './../../../../.');
require_once(__DIR__ . '/../Token.php');

use PHPUnit\Framework\TestCase;
use modules\patata\token\Token;

class Test extends TestCase
{
    public function testEncodeAndDecode()
    {
		$token = new Token();
		$data = 'Hello World';
		$myToken = $token->encode($data);
		$decoded = $token->decode($myToken);
		$this->assertEquals($data, $decoded);
    }
}
