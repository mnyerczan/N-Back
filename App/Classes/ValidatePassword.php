<?php

namespace App\Classes;


class ValidatePassword extends Validator 
{
    private $pass;
    
    public function __construct( $pass = null) 
    {
        $this->value = $pass;        

        parent::__construct();
    }

    public function getPass()
    {
        return $this->value;
    }

    public function validate() 
    {
        if (strlen($this->value) < 6 ) {
            $this->setError('Password is too short');
            return;
        }
        if (!preg_match("`^[a-zA-Z0-9_\.,áéöüóőúű'\"\\+!%/\=()?@]+$`",$this->value )) {
            $this->setError('Password contains invalid characters');
            return;
        }
        if (strlen($this->value) > 20 ) {
            $this->setError('Password is too long');
            return;
        }
    }
}
    