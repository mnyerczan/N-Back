<?php


use DB\EntityGateway;
use Login\UserEntity;
use Model\Sessions;


require_once APPLICATION.'Model/Validators/validator.php';
require_once APPLICATION.'Model/Validators/validateEmail.php';
require_once APPLICATION.'Model/Validators/validateUser.php';
require_once APPLICATION.'Model/Validators/validatePassword.php';

require_once APPLICATION.'Core/controller.php';


class signInController extends Controller
{

    function __construct( $matches )
    {                 

        parent::__construct();
        
        $this->SetDatas();
        $this->datas['email'] = $_POST['signIn-email'] ?? '';
 

        if ( @$matches['action'] )
        {
            $action = $matches['action'].'Action';
            
            $this->$action();
        }
        else
        {
            $this->Action();
        }                
    }

    function submitAction()
    {
        $email = new ValidateEmail( $_POST['signIn-email'] );
        $pass = new ValidatePassword( $_POST['signIn-pass'] );

        if ( $email->errorMsg || $pass->errorMsg )
        {            

            $this->datas['emailLabel']      = $email->errorMsg ?? 'email';
            $this->datas['passwordLabel']   = $pass->errorMsg ?? 'password';
            $this->datas['message']         = 'Sign In';
                    
            $this->View( $this->datas, [ 'view' => 'signIn', 'module' => 'User'] );

            return;
        }


        if ( !$this->user->Login( $_POST['signIn-email'], $_POST['signIn-pass'] ) )
        {

            $this->datas['emailLabel']      = $email->errorMsg ?? 'email';
            $this->datas['passwordLabel']   = $pass->errorMsg ?? 'password';
            $this->datas['message']         = 'Email or password is invalid!';
                    
            $this->View( $this->datas, [ 'view' => 'signIn', 'module' => 'User'] );

            return;
        }
                
        
        header( "Location: ".APPROOT );        
    }

    function Action()
    {    

        $this->datas['emailLabel'] = 'E-mail';
        $this->datas['passwordLabel'] = 'Password';
        $this->datas['message']         = 'Sign In';

        $this->View( $this->datas, [ 'view' => 'signIn', 'module' => 'User'] );
    }
}