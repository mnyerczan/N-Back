<?php


use DB\EntityGateway;
use Login\UserEntity;


require_once APPLICATION.'Model/seria.php';
require_once APPLICATION.'Model/home.php';
require_once APPLICATION.'Model/navbar.php';

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