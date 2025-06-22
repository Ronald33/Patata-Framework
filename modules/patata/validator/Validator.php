<?php
namespace patata\validator;

require_once(__DIR__ . '/Input.php');

class Validator
{
	private $inputs = [];
	private $inputsWithErrors = [];
	private $_paths = [];
	private $_clases = [];

	public function __construct()
	{
		$this->addSource(__DIR__ . DIRECTORY_SEPARATOR . 'Rule.php', 'patata\validator\Rule');
	}

	public function addSource($path, $name_class)
	{
		assert(file_exists($path), 'La fuente: ' . $path . ' no existe');
		array_push($this->_paths, $path);
		array_unshift($this->_clases, $name_class);
	}

	public function addInput($name, $value, $is_optional = false)
	{
		$input = new Input($this->_paths, $this->_clases, $name, $value, $is_optional);
		array_push($this->inputs, $input);
		return $input;
	}

	public function addInputFromArray($name, $array, $key, $is_optional = false)
	{
		$value = isset($array[$key]) ? $array[$key] : NULL;
		return $this->addInput($name, $value, $is_optional);
	}

	public function addInputFromObject($name, $object, $key, $is_optional = false)
	{
		$array = (array) $object;
		return $this->addInputFromArray($name, $array, $key, $is_optional);
	}
	
	public function hasErrors()
	{
		$this->inputsWithErrors = [];

		foreach($this->inputs as $input)
		{
			if(!$input->isValid())
			{
				$name = $input->getName();
				$messages = $input->getMessages();
                array_push($this->inputsWithErrors, ['name' => $name, 'messages' => $messages]);
			}
		}

		return sizeof($this->inputsWithErrors) > 0;
	}
	
	public function getInputsWithErrors() { return $this->inputsWithErrors; }
}
