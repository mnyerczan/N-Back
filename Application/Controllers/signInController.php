<?php


use DB\EntityGateway;
use Login\UserEntity;
use Model\Sessions;





class signInController extends MainController
{


    
    function __construct( $matches )
    {                 

        parent::__construct();
        
        $this->SetDatas();
        $this->datas['email'] = $_POST['signIn-email'] ?? '';
        $this->datas['signInIllustrate'] = BACKSTEP.APPLICATION.'Images/memory-bg.jpg';

 

        if ( @$matches['action'] )
        {
            $action = $matches['action'].'Action';
            
            $this->$action();
        }
        else
        {
            $this->FormAction();
        }                
    }




    function submitAction()
    {
        $email  = new ValidateEmail( $_POST['signIn-email'] );
        $pass   = new ValidatePassword( $_POST['signIn-pass'] );
        
        if ( !$this->user->Login($email->getEmail(), $pass->getPass()) )
        {
            $this->datas['emailLabel']      = $email->errorMsg ?? 'Email';
            $this->datas['passwordLabel']   = $pass->errorMsg ?? 'Password';
            $this->datas['message']         = 'Email or password is invalid!';
                    
            $this->Response( $this->datas, [ 
                'view'      => 'signIn', 
                'module'    => 'User',
                'layout'    => 'Main'
            ] );

            return;
        }                        
        $this->Response([], ['view' => 'redirect:'.APPROOT.'/']);
    }



    


    function FormAction()
    {    

        $this->datas['emailLabel']      = 'E-mail';
        $this->datas['passwordLabel']   = 'Password';
        $this->datas['message']         = 'Sign In';

        $this->Response( $this->datas, [ 
            'view'      => 'signIn', 
            'layout'    => 'Main' ,
            'module'    => 'User'
            ]
         );
    }
}