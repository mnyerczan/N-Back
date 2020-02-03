<?php

class ValidateUser extends Validator
{
    private $user;

    public function __construct( $userName )
    {

        $this->user = $userName;
        parent::__construct();
    }
    function validate() 
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->user )) 
        {
            $this->setError('Username contains invalid characters');
            return;
        }
        if (strlen($this->user) < 4 ) 
        {
            $this->setError('Username is too short');
            return;
        }
        if (strlen($this->user) > 20 ) 
        {
            $this->setError('Username is too long');
            return;
        }
    }
}
?>