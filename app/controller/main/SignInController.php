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

        parent::__construct();
        
        $this->SetDatas();
        $this->datas['email'] = $_POST['signIn-email'] ?? '';
        $this->datas['signInIllustrate'] = BACKSTEP.APPLICATION.'images/memory-bg.jpg';
               
    }



    function index()
    {    

        $this->datas['emailLabel']      = 'E-mail';
        $this->datas['passwordLabel']   = 'Password';
        $this->datas['message']         = 'Sign In';

        $this->Response( $this->datas, new ViewParameters('signIn', 'text/html',"", "User"));
    }


    function submit()
    {
        $email  = new ValidateEmail($_POST['signIn-email']);
        $pass   = new ValidatePassword($_POST['signIn-pass']);
        
        // Hibás autentikálás esetén hibaüzenettel visszatér
        if (!User::login($email->getEmail(), $pass->getPass()))
        {
            $this->datas['emailLabel']      = $email->errorMsg ?? 'Email';
            $this->datas['passwordLabel']   = $pass->errorMsg ?? 'Password';
            $this->datas['message']         = "<span style=\"color:red\">Email and/or password are invalid!</span>";
                    
            $this->Response( $this->datas, new ViewParameters("signIn", "", "", "User"));

            return;
        }               
        
        $this->Response([], new ViewParameters( "redirect:".APPROOT.'/'));
    }
    
}