<?php

namespace App\Controller\Main;


use App\Controller\Main\MainController;
use App\Model\ViewParameters;
use App\Classes\ValidatePassword;
use App\Classes\ValidateEmail;
use App\Services\User;


class SignInController extends MainController
{


    
    function __construct( $matches )
    {                       
        
        $this->SetDatas();
        $this->put('email', $_POST['signIn-email'] ?? '');
        $this->put('signInIllustrate', BACKSTEP.APPLICATION.'images/memory-bg.jpg');
               
    }



    function index()
    {    

        $this->put('emailLabel', 'E-mail');
        $this->put('passwordLabel', 'Password');
        $this->put('message', 'Sign In');

        $this->Response( new ViewParameters('signIn', 'text/html',"", "User"));
    }


    function submit()
    {
        $email  = new ValidateEmail($_POST['signIn-email']);
        $pass   = new ValidatePassword($_POST['signIn-pass']);
        
        // Hibás autentikálás esetén hibaüzenettel visszatér
        if (!User::login($email->getEmail(), $pass->getPass()))
        {
            $this->put("emailLabel", $email->errorMsg ?? "Email");
            $this->put("passwordLabel", $pass->errorMsg ?? "Password");
            $this->put("message", "<span style=\"color:red\">Email and/or password are invalid!</span>");
                    
            $this->Response( new ViewParameters("signIn", "", "", "User"));

            return;
        }               
        
        $this->Response( new ViewParameters( "redirect:".APPROOT.'/'));
    }
    
}