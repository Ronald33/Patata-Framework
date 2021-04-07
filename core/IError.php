<?php
namespace core;

interface IError
{
	public function showMessage($messageDevelopment, $messageProduction, $code = 500);
}
