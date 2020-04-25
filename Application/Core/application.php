<?php

use DB\EntityGateway;
use Login\UserEntity;



require_once "_globals.php";

require_once "converter.php";
require_once APPLICATION."functions.php";

final class Application
{
    private $user,
            $routes;   

    function route($cleanedUri)
    {                 
        $this->addRoute( '(?<controller>)/?' , 'HomeController' , 'get');    
                      
        $this->addRoute('(?<controller>signUp)/?' , 'signUpController' , 'get');                
        $this->addRoute('(?<controller>signUp)/(?<action>submit)' , 'signUpController', 'post' );        
        
        $this->addRoute('(?<controller>signIn)/?' , 'signInController' , 'get');
        $this->addRoute('(?<controller>signIn)/(?<action>submit)' , 'signInController', 'post' );
        $this->addRoute('(?<controller>logUot)' , 'logUotController' , 'get');
        
        $this->addRoute('(?<controller>user)/?' , 'userController','get' );
        $this->addRoute('(?<controller>settings)/?', 'settingsController','get' );        
        $this->addRoute('(?<controller>nBack)/?', 'nBackController','get' );        
        $this->addRoute('(?<controller>documents)/?' , 'documentsController','get' );
        
 
        foreach( $this->routes[$_SERVER['REQUEST_METHOD']] as $pattern => $controller )
        {                
            if( preg_match( $pattern, $cleanedUri, $matches ) )
            {                      
                require_once APPLICATION."Controllers/{$controller}.php";
                new $controller( $matches );
                die;
            }
        }
        
        new NotFoundController();
                        
    }    

    private function addRoute( string $pattern, string $controller, string $method )
    {
        $method = strtoupper($method);
        $this->routes[$method]["`^{$pattern}$`"] = $controller;
    }



    /**
     * Nincs implementálva....
     */
    function Session()
    {    
        if(isset($_GET['exit']))
        {				
            $result = ( EntityGateway::GetInstance() )->Select("UPDATE users SET `online` = 0 WHERE userName = :name ", [':name' => $_SESSION['userName']]);
    
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