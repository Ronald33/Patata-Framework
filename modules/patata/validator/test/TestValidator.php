<?php
define('PATH_BASE', './../../../../.');
require_once(PATH_BASE . '/modules/patata/error/Error.php');
require_once(__DIR__ . '/../Validator.php');

use PHPUnit\Framework\TestCase;
use modules\patata\validator\Validator;
use modules\patata\error\Error;

class TestValidator extends TestCase
{
	public function testAddInput()
    {
		$validator = new Validator();
		$validator->setError(new Error());
		$validator->addInput('Email', 'Ronald.dev1@gmailcom')->addRule('isEmail');
		$validator->addInput('Float', '3f')->addRule('isFloat');
		$validator->addInput('Not Required', NULL, false)->addRule('isEmail'); // Asignado como no requerido
		$validator->isValid();
		$this->assertEquals(2, sizeof($validator->getInputsWithErrors())); // Hay 2 inputs con errores
	}

	public function testAddInputFromArray()
    {
		$array = [
			'email' => 'Ronald.dev1@gmailcom', 
			'float' => '3f'
		];
		$validator = new Validator();
		$validator->setError(new Error());
		$validator->addInputFromArray('Email', $array, 'email')->addRule('isEmail');
		$validator->addInputFromArray('Float', $array, 'float')->addRule('isFloat');
		$validator->addInputFromArray('Not Required', $array, 'notRequired', false)->addRule('isEmail'); // Asignado como no requerido
		$validator->isValid();
		$this->assertEquals(2, sizeof($validator->getInputsWithErrors())); // Hay 2 inputs con errores
	}
}
