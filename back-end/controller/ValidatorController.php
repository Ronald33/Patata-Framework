<?php
require_once(LIBRARIES . 'Validate/Validator.php');
use Validate\Validator;

class ValidatorController
{
	private $model;
	private $view;
    
    public function index($var1 = null, $var2 = null)
    {
        $validator = new Validator();
        $validator->addValue('var1', $var1)->addRule('isFilled')->addRule('isWord')->addRule('isLess', 9);
        $validator->addValue('var2', $var2)->addRule('isFilled')->addRule('isInt')->addRule('isPositive');
        if($validator->isValid()) { echo 'Todo Ok'; }
        else
        {
            $inputsWithErrors = $validator->getInputsWithErrors();
            $inputsWithErrors_size = sizeof($inputsWithErrors);

            for($i = 0; $i < $inputsWithErrors_size; $i++)
            {
                $inputWithErrors = $inputsWithErrors[$i];
                $name = $inputsWithErrors[$i]['name'];
                $messages = $inputsWithErrors[$i]['messages'];
                echo 'Las variable <b>' . $name . '</b> asignada en Validator/index/{{var1}}/{{var2}} tiene los siguientes problemas:<br/>';
                
                foreach($messages as $message) { echo '- ' . $message . '<br/>'; }
            }
        }
    }
}