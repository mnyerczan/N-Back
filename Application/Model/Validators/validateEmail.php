<?php

class ValidateEmail extends Validator 
{

    var $email;

    function __construct( $email )
    {
        $this->email = $email;

        parent::__construct();
    }
    public function validate() 
    {        

        if ( !preg_match( "%^[a-zA-Z]{0,}[a-zA-z0-9.]{0,}@[a-zA-z0-9.]{0,}[a-zA-Z]{0,}\.\D{2,}$%", $this->email ) )
        {
            $this->setError( 'Invalid email address' );
            return;
        }
        if ( strlen( $this->email ) > 100 )
        {
            $this->setError('Address is too long');
            return;
        }
    }
}