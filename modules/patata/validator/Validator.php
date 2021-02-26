<?php
namespace modules\patata\validator;

require_once(__DIR__ . '/Input.php');

use \Core\IError;

class Validator
{
	private $inputs = array();
	private $valid = true;
	private $inputsWithErrors = array();
	private $error;

	public function setError(IError $error) { $this->error = $error; }
	
	public function addInput($name, $value)
	{
		if(isset(func_get_args()[2])) { $input = new Input($name, $value, func_get_args()[2]); }
		else { $input = new Input($name, $value); }
		$input->setError($this->error);
		array_push($this->inputs, $input);
		return $input;
	}

	public function addInputFromArray($name, $array, $key)
	{
		$value = isset($array[$key]) ? $array[$key] : NULL;
		if(isset(func_get_args()[3])) { return $this->addInput($name, $value, func_get_args()[3]); }
		else { return $this->addInput($name, $value); }
	}

	public function addInputFromObject($name, $object, $key)
	{
		$value = isset($object->$key) ? $object->$key : NULL;
		if(isset(func_get_args()[3])) { return $this->addInput($name, $value, func_get_args()[3]); }
		else { return $this->addInput($name, $value); }
	}
	
	private function validate()
	{
		foreach($this->inputs as $input)
		{
			if(!$input->isValid())
			{
				$this->valid = false;
				$name = $input->getName();
				$messages = $input->getMessages();
                array_push($this->inputsWithErrors, ['name' => $name, 'messages' => $messages]);
			}
		}
	}
	
	public function isValid() { $this->validate(); return $this->valid; }
	public function getInputsWithErrors() { return $this->inputsWithErrors; }
}
