<?php

namespace App\Classes;


class ValidateEmail extends Validator 
{

    private $email;




    function __construct( $email = null )
    {
        $this->value = $email;

        parent::__construct();
    }

    


    function getEmail()
    {
        return $this->value;
    }



    public function validate() 
    {       

        if ( !preg_match( "%^[a-zA-Z]{0,}[a-zA-z0-9.]{0,}@[a-zA-z0-9.]{0,}[a-zA-Z]{0,}\.\D{2,}$%", $this->value ) )
        {
            $this->setError( 'Invalid email address' );
            return;
        }
        if ( strlen( $this->value ) > 100 )
        {
            $this->setError('Address is too long');
            return;
        }
    }
}