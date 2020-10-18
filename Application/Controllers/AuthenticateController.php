<?php

class AuthenticateController extends MainController
{
    public function __construct()
    {

        parent::__construct();        
         
    }


    public function index()
    {               

        $_SESSION['authenticateCode'] = $authenticateCode = time().".".md5(rand(1000000, 9999999));

        $authenticateCodeHash = password_hash($authenticateCode, PASSWORD_BCRYPT);


        $this->Response( 
            [
                'authenticateCodeHash' => $authenticateCodeHash       
            ], 
            [ 
                'view' => '',
                'mime' => ' application/json'
            ] 
        );
    }
}