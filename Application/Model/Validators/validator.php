<?php

abstract class Validator
{
    
    private $errorMsg;

    public function __get( $name )
    {
        if ( $name == 'errorMsg' ) 
        {
            return $this->errorMsg;
        }            
    }

    public function __construct()
    {        
        $this->validate();    
    }
        

    public function validate(){}

    public function isValid()
    {
        if ( !@$this->errorMsg )
        {
            return TRUE;
        }            

        return FALSE;      
    }


    public function setError( $err )
    {
        $this->errorMsg = "<span class='error-span'>{$err}</span>";
    }
}

?>