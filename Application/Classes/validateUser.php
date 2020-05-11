<?php

class ValidateUser extends Validator
{
    private $isAdmin;



    public function __construct( $userName = null, $isAdmin = null)
    {
        $this->value = $userName;
        $this->isAdmin = $isAdmin;
        
        parent::__construct();
    }



    function getUser()
    {
        return $this->value;
    }




    function validate() 
    {
        if (!$this->value) return;
        
        if (!preg_match('/^[a-zA-Z0-9 _áéíóöőúüűÁÉÍÓÖŐÚÜŰ\.,]+$/',$this->value ))
        {                                    
            $this->setError('Username contains invalid characters!');            
            return false;
        }
        if (strlen($this->value) > 255)
        {
            $this->setError('Username is too long!');
        }                       
        if (!$this->isAdmin && preg_match('/^admin$/i',$this->value))
        {            
            $this->setError('Username cannot be Admin');
            return false;
        }      
        if ( !$this->isAdmin && strlen($this->value) < 6 ) 
        {
            $this->setError('Username is too short');
            return false;
        }
        if (strlen($this->value) > 255 ) 
        {
            $this->setError('Username is too long');
            return false;
        }    
    }


}