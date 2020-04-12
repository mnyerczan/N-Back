<?php

use DB\EntityGateway;
use Login\UserEntity;
use Model\Sessions;

require_once APPLICATION.'Model/sessions.php';
require_once APPLICATION.'Model/seria.php';
require_once APPLICATION.'Model/home.php';
require_once APPLICATION.'Model/navbar.php';
require_once APPLICATION.'Model/indicator.php';
require_once APPLICATION.'Model/header.php';

class Controller
{    
    // osztály invariáns - az osztály lehetéges állapotait írja le
    protected   $user,
                $datas,
                $db;                

    function __construct()
    {
        $this->user = UserEntity::GetInstance();  
        $this->db   = EntityGateway::GetInstance();
    }

/**
 * Előfeltétel - bemeneti paraméterek
 * 
 * @param array $datas Get datas for render views
 * @param array $viewModule Get name of view and module
 */
    protected function View (array $datas = [], array $viewModule = []): void // utófeltétel - visszatérési érték, objektum állapot változás
    {             
        extract( $datas );   
        extract( $viewModule ); 
         
        
        unset( $datas ); 
        unset( $viewModule );    
            

        require_once APPLICATION."Templates/_layout.php";     
    }   
    
    protected function SetDatas()
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
            'header' => (new Header( $this->user ))->datas
        ];       
           
    }

}