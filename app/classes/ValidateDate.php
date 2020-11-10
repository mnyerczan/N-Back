<?php

namespace App\Classes;


class ValidateDate extends Validator 
{
    private $date;
    
    public function __construct( $date = null ) 
    {
        $this->value = $date;        

        parent::__construct();
    }

    function getDate()
    {
        return $this->value;
    }

    public function validate() 
    {     

        $currentDate    = time();
        $gettedDate     = strtotime( $this->value );
        
     
        if ( !preg_match("%^\d{4}-\d{2}-\d{2}$%", $this->value) || $currentDate < $gettedDate ) 
        {
            $this->setError('Invalide date! Maybe the value is larger then current date.');
        }
    }
}
    
