<?php

class ValidateUser extends Validator
{
    private $isAdminAtuhentic;



    public function __construct( $userName = null, $isAdminAtuhentic = null)
    {
        $this->value = $userName;
        $this->admin = $isAdminAtuhentic;
        
        parent::__construct();
    }



    function getUser()
    {
        return $this->value;
    }




    function validate() 
    {
        if (!$this->value) return;
        
        if (!preg_match('/^[a-zA-Z0-9 _áéíóöőúüűÁÉÍÓÖŐÚÜŰ]+$/',$this->value ))
        {                                    
            $this->setError('Username contains invalid characters!');            
            return false;
        }
        if (strlen($this->value) > 255)
        {
            $this->setError('Username is too long!');
        }                
        if (!$this->isAdminAtuhentic && preg_match('/^admin$/i',$this->value))
        {            
            $this->setError('Username cannot be Admin');
            return false;

        }
        if (strlen($this->value) < 6)
        {
            $this->setError('Username is too short!');
        }
        if (strlen($this->value) < 4 ) 
        {
            $this->setError('Username is too short');
            return false;
        }
        if (strlen($this->value) > 20 ) 
        {
            $this->setError('Username is too long');
            return false;
        }    
    }


}