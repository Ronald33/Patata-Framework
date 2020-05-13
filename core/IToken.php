<?php
namespace core;

interface IToken
{
	public function encode($payload);
	public function decode($token);
}
