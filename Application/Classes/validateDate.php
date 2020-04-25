<?php

class ValidateDate extends Validator 
{
    private $date;
    
    public function __construct( $date = null ) 
    {
        $this->date = $date;        

        parent::__construct();
    }

    function getDate()
    {
        return $this->date;
    }

    public function validate() 
    {
        $currentDate    = time();
        $gettedDate     = strtotime( $this->date );
        
     
        if ( !preg_match("%^\d{4}-\d{2}-\d{2}$%", $this->date) || $currentDate < $gettedDate ) 
        {
            $this->setError('Invalide date! Maybe the value is lower then current date.');
        }
    }
}
    
