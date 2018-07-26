<?php
namespace Validate;
require_once(LIBRARIES . '/Validate/Rule.php');
require_once(LIBRARIES . '/Validate/Message.php');
require_once(LIBRARIES . '/Validate/core/Helper.php');
require_once('core/Error/Error.php');
use Error\Error;

class Element
{
	private $name;
	private $value;
	private $allowNull;
	private $valid = true;
	private $messages = array();
	
	public function __construct($name, $value, $allowNull = false)
	{
		$this->name = $name;
		$this->value = $value;
		$this->allowNull = $allowNull;
	}
	
	public function setAllowNull($allowNull) { $this->allowNull = $allowNull; return $this; }
	public function getAllowNull() { return $this->allowNull; }
	
	public function addRule($type)
	{
		$args = func_get_args();
        array_shift($args);
        array_unshift($args, $this->value);
        
        if(Helper::existsRule($type))
		{
			$result = call_user_func_array(array(__NAMESPACE__ . '\Rule', $type), $args);
			if(!$this->allowNull || ($this->allowNull && !empty($this->value)))
			{
				if(!$result) { $this->valid = false; array_push($this->messages, Message::get($type)); }
			}
		}
		else { Error::show(Message::noRule($type)); }
		
		return $this;
	}
	
	public function getName() { return $this->name; }
	public function getMessages() { return $this->messages; }
    public function getError() { return  array('name' => $this->name, 'messages' => $this->getMessages()); }
	public function isValid() { return $this->valid; }
}