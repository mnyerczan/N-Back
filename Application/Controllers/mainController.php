<?php

use Login\UserEntity;
use Model\Sessions;

require_once APPLICATION.'Model/sessions.php';
require_once APPLICATION.'Model/seria.php';
require_once APPLICATION.'Model/home.php';
require_once APPLICATION.'Model/navbar.php';
require_once APPLICATION.'Model/indicator.php';

require_once APPLICATION.'Core/controller.php';

class MainController extends Controller
{
    private $user,    
            $datas;


    function __construct($matches)
    {
               

        $this->user = UserEntity::GetInstance();
        $this->datas = $this->GetDatas();
                

        $this->Action();
    }

    private function GetDatas()
    {
        return [ 
            'seria'     => new Seria( $this->user->id ), 
            'user'      => $this->user, 
            'home'      => ( new Home() )->getContent() ,           
            'navbar'    => ( new Navbar( $this->user ) )->getDatas(),
            'indicator' => (Indicator::getInstance( 
                new Sessions( $this->user->id, 1 ),
                $this->user->gameMode
            ))->getDatas()
        ];
    }    
  
    function Action()
    {                            
        $this->View( $this->datas, ['view' => 'main', 'module' => 'Main'] );
    }
                 
}