<?php
namespace core\rest\token;

interface IToken
{
	public function encode($payload);
	public function decode($token);
}