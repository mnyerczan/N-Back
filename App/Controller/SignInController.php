<?php

namespace App\Controller;


use App\Core\MainController;
use App\Model\ViewParameters;
use App\Classes\ValidatePassword;
use App\Classes\ValidateEmail;
use App\Classes\ValidateUser;
use App\Classes\ValidateDate;
use App\Classes\ValidateSex;
use App\Classes\ImageConverter;


class SignInController extends MainController
{


    
    function __construct( $matches )
    {                 

        parent::__construct();
        
        $this->SetDatas();
        $this->datas['email'] = $_POST['signIn-email'] ?? '';
        $this->datas['signInIllustrate'] = BACKSTEP.APPLICATION.'Images/memory-bg.jpg';
               
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
        $email  = new ValidateEmail( $_POST['signIn-email'] );
        $pass   = new ValidatePassword( $_POST['signIn-pass'] );
        
        if ( !$this->user->Login($email->getEmail(), $pass->getPass()) )
        {
            $this->datas['emailLabel']      = $email->errorMsg ?? 'Email';
            $this->datas['passwordLabel']   = $pass->errorMsg ?? 'Password';
            $this->datas['message']         = 'Email or password is invalid!';
                    
            $this->Response( $this->datas, new ViewParameters("SignIn", "", "", "User"));

            return;
        }               
        
        $this->Response([], new ViewParameters( "redirect:".APPROOT.'/'));
    }
    
}