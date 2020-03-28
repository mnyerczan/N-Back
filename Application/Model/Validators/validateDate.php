<?php

class ValidateDate extends Validator 
{
    private $date;
    
    public function __construct( $date ) 
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
        $currentDate    = strtotime(date('Y-m-d'));
        $gettedDate     = strtotime( $this->date );


        if ( $currentDate < $gettedDate ) 
        {
            $this->setError('Invalide date! Value must be lower then current date!');

        }
    }
}
    
