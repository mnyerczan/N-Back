<?php

use DB\EntityGateway;
use Login\UserEntity;

require_once APPLICATION.'Model/userEntity.php';
require_once APPLICATION.'Interfaces/DBInterface.php';
require_once APPLICATION.'DB/MySql.php';
require_once APPLICATION.'DB/entityGateway.php';
require_once APPLICATION.'Core/controller.php';

require_once "_globals.php";

require_once "converter.php";
require_once APPLICATION."functions.php";

final class Application
{
    private $user;


    function __construct()
    {

    /**
     * Create user object
     */
        $this->user = new UserEntity();  
        $this->user->Load();
        
       
    }

    function route()
    {
        $page = '';
        

        if(preg_match( '%^/$%', $_SERVER['REQUEST_URI'] ))
            $page = 'main';
        else
            $page = '_404';

        
        require_once APPLICATION."Controllers/{$page}Controller.php";

        $controller = $page.'Controller';


        new $controller($this->user);
    }    



    function Session()
    {    

        if(isset($_GET['exit']))
        {				
            $result = ( EntityGateway::getDB() )->Select("UPDATE users SET `online` = 0 WHERE u_name = :name ", [':name' => $_SESSION['u_name']]);
    
            if( !$result && @$_COOKIE[session_name()])
            {
                setcookie(session_name(), '', time()-42000, '/');
            }
    
            header("Location: index.php?");
            die;
        }
        else
        {				
            if( @$_SESSION['u_name'] )
            {			
                $this->user->Load( $_SESSION['u_name'], $_SESSION['password'] );
            }					
        }
    }
}