<?php namespace FiveSay;

class ValidationException extends \RuntimeException
{
	
    public $validator;
    
    public $errors;

    public function __construct($validator, $code, Exception $previous = null)
    {
        switch ($code) {
        	case 0:
		        $this->validator = $validator;
		        $this->errors    = $validator->errors();
        		break;
        	case 1:
		        $this->errors    = $validator;
        		break;
        }
        	
        parent::__construct('ValidationFail', $code, $previous);
    }


}
