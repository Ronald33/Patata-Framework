<?php
namespace patata\validator;

class Input
{
	private $paths;
	private $classes;
	private $errorMessages;
	private $name;
	private $value;
	private $optional;
	private $valid = true;
	private $messages = [];
	
	public function __construct($paths, $classes, $name, $value, $optional = false)
	{
		$this->paths = $paths;
		$this->classes = $classes;
		$this->errorMessages = [];
		$this->name = $name;
		$this->value = $value;
		$this->optional = $optional;

		$this->loadFiles();
		$this->setErrorMessages();
	}

	private function setErrorMessages()
	{
		$size = sizeof($this->classes);
		for($i = $size; $i > 0; $i--)
		{
			$tmp = new $this->classes[$i-1];
			if(is_callable([$tmp, 'getMessages']))
			{
				$messages = $tmp->getMessages();
				if(is_array($messages)) { $this->errorMessages = array_merge($this->errorMessages, $tmp->getMessages()); }
			}
		}
	}

	private function loadFiles()
	{
		foreach($this->paths as $path) { require_once($path); }
	}
	
	public function addRule($type)
	{
		if(($this->value === '' || $this->value === NULL || $this->value === []) && $this->optional) { return $this; }

		$message = -1;
		if(is_array($type))
		{
			$message = $type[1];
			$type = $type[0];
		}

		$callable = $this->getCallable($type);
		assert($callable !== false, 'La regla ' . $type . ' no existe');
		
		$args = func_get_args();		
		
        array_shift($args);
        array_unshift($args, $this->value);
        
		$result = call_user_func_array([$callable, $type], $args);
		
		if(!$result)
		{
			$this->valid = false;
			if($message === -1) { $message = $this->getMessageByRule($type); }
			array_push($this->messages, $message);
		}

		return $this;
	}

	public function addCustomRule($test, $message = '')
	{
		if(!$test)
		{
			array_push($this->messages, $message === '' ? $this->getMessageByRule('default') : $message);
			$this->valid = false;
		}

		return $this;
	}

	public function getCallable($method)
	{
		foreach($this->classes as $class)
		{
			if(is_callable([$class, $method])) { return $class; }
		}

		return false;
	}

	private function getMessageByRule($rule)
	{
		return isset($this->errorMessages[$rule]) ? $this->errorMessages[$rule] : $this->errorMessages['default'];
	}
	
	public function getName() { return $this->name; }
	public function getMessages() { return $this->messages; }
	public function isValid() { return $this->valid; }
}
