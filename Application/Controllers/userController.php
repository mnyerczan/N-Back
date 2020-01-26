<?php

use DB\EntityGateway;
use Login\UserEntity;
use Model\Sessions;

require_once APPLICATION.'Model/sessions.php';
require_once APPLICATION.'Model/seria.php';
require_once APPLICATION.'Model/home.php';
require_once APPLICATION.'Model/navbar.php';
require_once APPLICATION.'Model/indicator.php';

require_once APPLICATION.'Core/controller.php';

class userController extends Controller
{
    private $user;
    private $datas;
    private $database;


    function __construct($matches)
    {
        
        $action = $matches['action'].'Action';
             
        $this->database = EntityGateway::getDB();
        $this->user = UserEntity::GetInstance();
        $this->datas = $this->GetDatas();
                

        $this->$action();
    }

    private function GetDatas()
    {
        return [ 
            'seria' => new Seria( $this->user->id ), 
            'user'  => $this->user,            
            'navbar'=> ( new Navbar( $this->user ) )->getDatas(),
            'indicator' => (Indicator::getInstance( new Sessions( $this->user->id, 1 ) ))->getDatas()
        ];
    }

    function signUpAction()
    {    
        $this->datas['isAdmin'] = $this->database->getUsersCount()[0]-> num <= 1 ? 0 : 1;
  

        $this->View( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );
    }


                 
}