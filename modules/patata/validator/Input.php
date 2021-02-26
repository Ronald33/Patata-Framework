<?php
namespace modules\patata\validator;

require_once(__DIR__ . '/Rule.php');
require_once(__DIR__ . '/Message.php');
require_once(__DIR__ . '/Helper.php');

require_once(PATH_BASE . '/core/IError.php');

use \Core\IError;

class Input
{
	private $name;
	private $value;
	private $required;
	private $valid = true;
	private $messages = array();
    private $error;
	
	public function __construct($name, $value, $required = true)
	{
		$this->name = $name;
		$this->value = $value;
		$this->required = $required;
	}

    public function setError(IError $error) { $this->error = $error; }
	
	public function addRule($type)
	{
		$args = func_get_args();
		$message = -1;
		if(is_array($type))
		{
			$message = $type[1];
			$type = $type[0];
		}
        array_shift($args);
        array_unshift($args, $this->value);
        
        if(Helper::existsRule($type))
		{
			$result = call_user_func_array(array(__NAMESPACE__ . '\Rule', $type), $args);
			if($this->required || (!$this->required && !empty($this->value)))
			{	
				if(!$result)
				{
					$this->valid = false;
					if($message === -1) { $message = Message::get($type); }
					array_push($this->messages, $message);
				}
			}
		}
		else { $this->error->showMessage(Message::noRule($type), Message::byDefault()); }

		return $this;
	}
	
	public function getName() { return $this->name; }
	public function getMessages() { return $this->messages; }
	public function isValid() { return $this->valid; }
}