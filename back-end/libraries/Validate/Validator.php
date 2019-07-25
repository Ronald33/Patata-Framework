<?php
namespace Validate;
require_once(LIBRARIES . 'Validate/core/Element.php');
class Validator
{
	private $elements = array();
	private $valid = true;
	private $inputsWithErrors = array();
	
	public function addValue($name, $value, $allowNull = false)
	{
		$element = new Element($name, $value, $allowNull);
		array_push($this->elements, $element);
		return $element;
	}
	public function addGet($name, $value) { return $this->addValue($name, $_GET[$value]); }
	public function addPost($name, $value) { return $this->addValue($name, $_POST[$value]); }
	
	private function validate()
	{
		//\Helper::print_r($this->elements);
		foreach($this->elements as $element)
		{
			//\Helper::print_r($element);
			if(!$element->isValid())
			{
				$this->valid = false;
				$name = $element->getName();
				$messages = $element->getMessages();
                array_push($this->inputsWithErrors, array('name' => $name, 'messages' => $messages));
			}
		}
	}
	
	public function getInputsWithErrors() { return $this->inputsWithErrors; }
	public function isValid() { $this->validate(); return $this->valid; }
}