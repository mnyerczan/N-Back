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

    function __construct()
    {
        session_start();
    }

    function route($cleanedUri)
    {                                
        $this->addRoute( '(?<controller>)/?' , 'HomeController' , 'get');    
                      
        $this->addRoute('(?<controller>signUp)/(?<action>form)/?' , 'signUpController' , 'get');                
        $this->addRoute('(?<controller>signUp)/(?<action>submit)/?' , 'signUpController', 'POST' );        
        
        $this->addRoute('(?<controller>signIn)/?' , 'signInController' , 'get');
        $this->addRoute('(?<controller>signIn)/(?<action>submit)' , 'signInController', 'POST' );
        $this->addRoute('(?<controller>logUot)' , 'logUotController' , 'get');
        
        $this->addRoute('(?<controller>user)/?' , 'userController','get', true );
        $this->addRoute('(?<controller>user)/(?<action>update)/?' , 'userController','POST', true );
        $this->addRoute('(?<controller>settings)/?', 'settingsController','get' );        
        $this->addRoute('(?<controller>nBack)/?', 'nBackController','get' );        
        $this->addRoute('(?<controller>documents)/?' , 'documentsController','get' );
        

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $pattern => $params)
        {                
            if (preg_match( $pattern, $cleanedUri, $matches ))
            {                      
                if ($params['logged'] && isset($_SESSION['userId']) || !$params['logged'])
                {                       
                    require_once APPLICATION."Controllers/{$params['controller']}.php";
                    new $params['controller']($matches);
                    die;
                }                
            }
        }
        
        new NotFoundController();
                        
    }    

    private function addRoute( string $pattern, string $controller, string $method, $logged = false )
    {
        $method = strtoupper($method);

        $this->routes[$method]["`^{$pattern}$`"] = [ 
            'controller'    => $controller,
            'logged'        => $logged
        ];
    }



    /**
     * Nincs implementálva....
     * 
     * 
     * 
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