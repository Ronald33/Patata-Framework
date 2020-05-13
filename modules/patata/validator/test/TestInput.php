<?php
define('PATH_BASE', './../../../../.');
require_once(PATH_BASE . '/modules/patata/error/Error.php');
require_once(__DIR__ . '/../Input.php');


use PHPUnit\Framework\TestCase;
use modules\patata\validator\Input;
use modules\patata\error\Error;

class TestInput extends TestCase
{
	public function testEmail()
    {
		$input = new Input('Email', 'Ronald.dev1@gmail.com');
		$input->setError(new Error());
		$input->addRule('isEmail');
		$this->assertEquals(0, sizeof($input->getMessages()));
	}

	public function testFloat()
    {
		$input = new Input('Float', '3');
		$input->setError(new Error());
		$input->addRule('isFloat');
		$this->assertEquals(0, sizeof($input->getMessages()));
	}

	public function testBetween()
    {
		$input = new Input('Between', '3');
		$input->setError(new Error());
		$input->addRule('isBetween', 10, 20);
		$this->assertEquals(1, sizeof($input->getMessages())); // Existe 1 error
	}

	public function testUrl()
    {
		$input = new Input('Url', 'http://www.google.com');
		$input->setError(new Error());
		$input->addRule('isUrl');
		$this->assertEquals(0, sizeof($input->getMessages()));
	}

	public function testFilled()
    {
		$input = new Input('Filled', '   ');
		$input->setError(new Error());
		$input->addRule('isFilled');
		$this->assertEquals(1, sizeof($input->getMessages())); // Existe 1 error
	}

	public function testMultiple()
    {
		$input = new Input('Multiple', '!"#$%&/()=?');
		$input->setError(new Error());
		$input->addRule('lengthIsLessThan', 5)->addRule('isAlphaNumericAndSpaces');
		$this->assertEquals(2, sizeof($input->getMessages())); // Existen 2 errores
	}

	public function testNotRequired()
    {
		$input = new Input('NotRequired', NULL, false); // Asignando el campo como no requerido
		$input->setError(new Error());
		$input->addRule('lengthIsLessThan', 5)->addRule('isAlphaNumericAndSpaces');
		$this->assertEquals(0, sizeof($input->getMessages()));
	}

	public function testNotRequiredWithContent()
    {
		$input = new Input('NotRequired', '!"#$%&/()=?', false); // Asignando el campo como no requerido
		$input->setError(new Error());
		$input->addRule('lengthIsLessThan', 5)->addRule('isAlphaNumericAndSpaces');
		$this->assertEquals(2, sizeof($input->getMessages())); // Existen 2 errores
	}
}
