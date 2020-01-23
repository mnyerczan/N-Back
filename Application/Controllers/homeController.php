<?php

use DB\EntityGateway;
use Login\UserEntity;

require_once APPROOT.'Class/home.php';

class homeController extends Controller
{
    private $user;

    function __construct( UserEntity $user )
    {
        $this->user = $user;

        $this->Action();
    }


    function Action()
    {               
        
        $datas = [ 
            'seria' => new Seria( $this->user->id ), 
            'user'  => $this->user,
            'home'  => (new Home())->getContent()
        ];


        $this->View( 'home', $datas );
    }
                 
}