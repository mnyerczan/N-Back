<?php

class ValidateSex extends Validator
{
    
    public function __construct( $sex = null) 
    {
        $this->value = $sex;        

        parent::__construct();
    }

    public function getSex()
    {
        return $this->value;
    }

    public function validate() 
    {
        if (!$this->value) return;

       
        if (!in_array($this->value, ['male','female']) ) 
        {
            $this->setError('Sour sex must be felame or male!');
            return;
        }     
    }
}