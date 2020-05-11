<?php

class AuthenticateController extends MainController
{
    public function __construct($matches)
    {
        parent::__construct();

        $action = @$matches['action'] ? $matches['action'].'Action' : 'EmitAction';
        
        $this->$action(); 
    }


    private function EmitAction()
    {
       
        $authenticateCode = time().".".md5(rand(1000000, 9999999));

        $_SESSION['authenticateCode'] = $authenticateCode;

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