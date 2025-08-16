<?php
namespace core\rest\tokenManager;

interface ITokenManager
{
	public function encode($payload);
	public function decode($token);
}