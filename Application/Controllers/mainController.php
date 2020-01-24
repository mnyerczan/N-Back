<?php


use DB\EntityGateway;
use Login\UserEntity;


require_once APPROOT.'Class/seria.php';
require_once APPROOT.'Class/home.php';
require_once APPROOT.'Class/navbar.php';

class MainController extends Controller
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
            'home'  => ( new Home() )->getContent(),
            'navbar'=> ( new Navbar( $this->user ) )->getDatas()
        ];



        $this->View( 'home', $datas );
    }
                 
}