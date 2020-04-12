<?php

use DB\EntityGateway;
use Login\UserEntity;

require_once APPLICATION.'Model/userEntity.php';
require_once APPLICATION.'Interfaces/DBInterface.php';
require_once APPLICATION.'DB/MySql.php';
require_once APPLICATION.'DB/EntityGateway.php';


require_once "_globals.php";

require_once "converter.php";
require_once APPLICATION."functions.php";

final class Application
{
    private $user,
            $routes;   

    function route()
    {                 
        $this->addRoute( $this->Path( APPROOT.'(?<controller>)/?' ), 'mainController' , 'get');    
        //$this->addRoute( $this->Path( APPROOT.'(?<controller>user)' ), 'userController' );
              
        $this->addRoute( $this->Path( APPROOT.'(?<controller>signUp)/?' ), 'signUpController' , 'get');                
        $this->addRoute( $this->Path( APPROOT.'(?<controller>signUp)/(?<action>submit)' ), 'signUpController', 'post' );        
        
        $this->addRoute( $this->Path( APPROOT.'(?<controller>signIn)/?' ), 'signInController' , 'get');
        $this->addRoute( $this->Path( APPROOT.'(?<controller>signIn)/(?<action>submit)' ), 'signInController', 'post' );
        $this->addRoute( $this->Path( APPROOT.'(?<controller>logUot)' ), 'logUotController' , 'get');
        
        $this->addRoute( $this->Path( APPROOT.'(?<controller>user)/?' ), 'userController','get' );
        $this->addRoute( $this->Path( APPROOT.'(?<controller>settings)/?' ), 'settingsController','get' );        
        $this->addRoute( $this->Path( APPROOT.'(?<controller>nBack)/?' ), 'nBackController','get' );        
        $this->addRoute( $this->Path( APPROOT.'(?<controller>documents)/?' ), 'documentsController','get' );
                        

        foreach( $this->routes[$_SERVER['REQUEST_METHOD']] as $pattern => $controller )
        {                
            if( preg_match( $pattern, URI, $matches ) )
            {      
                require_once APPLICATION."Controllers/{$controller}.php";
                new $controller( $matches );
                die;
            }
        }

        require_once APPLICATION."Controllers/_404Controller.php";
        new _404Controller();
                        
    }    

    private function addRoute( string $pattern, string $controller, string $method )
    {
        $method = strtoupper($method);
        $this->routes[$method][$pattern] = $controller;
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