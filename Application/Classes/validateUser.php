<?php

class ValidateUser extends Validator
{

    private $user;



    public function __construct( $userName = null)
    {
        $this->value = $userName;
        
        parent::__construct();
    }



    function getUser()
    {
        return $this->value;
    }




    function validate() 
    {
        if (!$this->value) return;

        if (!preg_match('/^[a-zA-Z0-9 _áéíóöőúüűÁÉÍÓÖŐÚÜŰ]{6,255}$/',$this->value )) 
        {
            $this->setError('Username contains invalid characters');
            return;
        }
        if (strlen($this->value) < 4 ) 
        {
            $this->setError('Username is too short');
            return;
        }
        if (strlen($this->value) > 20 ) 
        {
            $this->setError('Username is too long');
            return;
        }    
    }


}