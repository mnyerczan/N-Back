<?php

class ValidateAbout extends Validator
{
    
    public function __construct( $text = null) 
    {
        $this->value = trim($text);        

        parent::__construct();
    }

    public function getText()
    {
        return $this->value;
    }

    public function validate() 
    {
        if (!$this->value) return;

       
        if (!preg_match('/^[a-zA-Z0-9 _áéíóöőúüűÁÉÍÓÖŐÚÜŰ]*$/',$this->value )) 
        {
            $this->setError('Text contains invalid characters');
            return;
        }
        if (strlen($this->value) > 255 ) 
        {
            $this->setError('Text is too long');
            return;
        }
    }
}