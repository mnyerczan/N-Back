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

class signUpController extends Controller
{
    private $user;
    private $datas;
    private $database;


    function __construct($matches)
    {                 
            
        $this->database = EntityGateway::getDB();
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

    function Action()
    {    
        $this->SetDatas();

        $this->datas['nameLabel'] = 'Name';
        $this->datas['emailLabel'] = 'E-mail';
        $this->datas['dateLabel'] = 'Date of birth';
        $this->datas['passwordLabel'] = 'Password';
        


        $this->View( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );
    }

    private function submitAction()
    {

        $mail = new ValidateEmail( $_POST['create-user-mail'] );
        $pass = new ValidatePassword( $_POST['create-user-pass'] );
        $name = new ValidateUser( $_POST['create-user-name'] );


        if ( $mail->errorMsg || $pass->errorMsg || $name->errorMsg )
        {      
            $this->SetDatas();

            $this->datas['nameLabel'] = $name->errorMsg;
            $this->datas['emailLabel'] = $mail->errorMsg;
            $this->datas['dateLabel'] = 'Date of birth'; //$mail->errorMsg;
            $this->datas['passwordLabel'] = $pass->errorMsg;

            $this->View( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );
        
            return;
        }


        var_dump( $mail );
        var_dump( $name );
        var_dump( $pass );


        var_dump( $_POST );
        die;

        header("Location: ".APPROOT);
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
            )->getDatas()
        ];

        $this->datas['isAdmin'] = $this->database->getUsersCount()[0]->num <= 1 ?  'Admin' : NULL;
        $this->datas['enableNameInput'] = $this->datas['isAdmin'] ? 'readonly' : ''; 

        
    }
                 
}