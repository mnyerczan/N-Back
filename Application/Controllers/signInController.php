<?php


use DB\EntityGateway;
use Login\UserEntity;
use Model\Sessions;

require_once APPLICATION.'Model/sessions.php';
require_once APPLICATION.'Model/seria.php';
require_once APPLICATION.'Model/home.php';
require_once APPLICATION.'Model/navbar.php';
require_once APPLICATION.'Model/indicator.php';
require_once APPLICATION.'Model/Validators/validator.php';
require_once APPLICATION.'Model/Validators/validateEmail.php';
require_once APPLICATION.'Model/Validators/validateUser.php';
require_once APPLICATION.'Model/Validators/validatePassword.php';

require_once APPLICATION.'Core/controller.php';


class signInController extends Controller
{
    private $user;
    private $datas;


    function __construct( $matches )
    {                 
        $this->user = UserEntity::GetInstance();  
                
 

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
            $this->SetDatas();

            $this->datas['emailLabel']      = $email->errorMsg ?? 'email';
            $this->datas['passwordLabel']   = $pass->errorMsg ?? 'password';
            $this->datas['message']         = 'Sign In';
                    
            $this->View( $this->datas, [ 'view' => 'signIn', 'module' => 'User'] );

            return;
        }


        if ( !$this->user->Login( $_POST['signIn-email'], $_POST['signIn-pass'] ) )
        {
            $this->SetDatas();

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
        $this->SetDatas();

        $this->datas['emailLabel'] = 'E-mail';
        $this->datas['passwordLabel'] = 'Password';
        $this->datas['message']         = 'Sign In';

        $this->View( $this->datas, [ 'view' => 'signIn', 'module' => 'User'] );
    }

    private function SetDatas()
    {        

        $this->datas = [ 
            'seria' => new Seria( $this->user->id ), 
            'user'  => $this->user,            
            'navbar'=> ( new Navbar( $this->user ) )->getDatas(),
            'indicator' => (
                Indicator::getInstance(
                    new Sessions( $this->user->id, 1 ),
                    $this->user->gameMode 
                )
            )->getDatas(),
            'email' => $_POST['signIn-email'] ?? ''
        ];
        
    }
}