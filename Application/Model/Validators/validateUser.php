<?php

class ValidateUser extends Validator
{

    private $user;



    public function __construct( $userName = null)
    {
        $this->user = $userName;
        
        parent::__construct();
    }



    function getUser()
    {
        return $this->user;
    }




    function validate() 
    {
        if (!preg_match('/^\D+$/',$this->user )) 
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