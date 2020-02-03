<?php

use DB\EntityGateway;
use Login\UserEntity;

require_once APPLICATION.'Model/userEntity.php';
require_once APPLICATION.'Interfaces/DBInterface.php';
require_once APPLICATION.'DB/MySql.php';
require_once APPLICATION.'DB/entityGateway.php';


require_once "_globals.php";

require_once "converter.php";
require_once APPLICATION."functions.php";

final class Application
{
    private $user,
            $routes;   

    function route()
    {                 
        $this->addRoute( $this->Path( APPROOT.'(?<controller>)' ), 'mainController' );
        //$this->addRoute( $this->Path( APPROOT.'(?<controller>user)' ), 'userController' );
      
        $this->addRoute( $this->Path( APPROOT.'(?<controller>signUp)' ), 'signUpController' );
        $this->addRoute( $this->Path( APPROOT.'(?<controller>signUp)/(?<action>submit)' ), 'signUpController' );
        //$this->addRoute( $this->Path( APPROOT.'(?<controller>user)/(?<action>signIn)' ), 'userController' );

        

        foreach( $this->routes as $pattern => $controller )
        {    
            if( preg_match( $pattern, URI, $matches ) )
            {      

                require_once APPLICATION."Controllers/{$controller}.php";
                new $controller($matches);
                die;
            }
        }

        require_once APPLICATION."Controllers/_404Controller.php";
        new _404Controller();
                        
    }    

    private function addRoute( string $action, string $controller )
    {
        $this->routes[$action] = $controller;
    }


    private function Path( string $path ): string
    {
        return "`^{$path}$`";
    }

    function Session()
    {    

        if(isset($_GET['exit']))
        {				
            $result = ( EntityGateway::getDB() )->Select("UPDATE users SET `online` = 0 WHERE userName = :name ", [':name' => $_SESSION['userName']]);
    
            if( !$result && @$_COOKIE[session_name()])
            {
                setcookie(session_name(), '', time()-42000, APPROOT);
            }
    
            header("Location: index.php?");
            die;
        }
        else
        {				
            if( @$_SESSION['userName'] )
            {			
                $this->user->Load( $_SESSION['userName'], $_SESSION['password'] );
            }					
        }
    }
}