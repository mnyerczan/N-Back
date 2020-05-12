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

    function route($cleanedUri): void
    {                                
        $this->addRoute( '(?<controller>)/?' , 'HomeController' , 'get');    
                      
        $this->addRoute('(?<controller>signUp)/(?<action>form)/?' , 'signUpController' , 'get');                
        $this->addRoute('(?<controller>signUp)/(?<action>submit)/?' , 'signUpController', 'POST' );        
        
        $this->addRoute('(?<controller>signIn)/?' , 'signInController' , 'get');
        $this->addRoute('(?<controller>signIn)/(?<action>submit)' , 'signInController', 'POST' );
        $this->addRoute('(?<controller>logUot)' , 'logUotController' , 'get');
        
        $this->addRoute('(?<controller>account)/?' , 'AccountController','get', true );
        $this->addRoute('(?<controller>settings)/?', 'SettingsController','get' ); 
        $this->addRoute('(?<controller>settings)/(?<action>personal)/?' , 'SettingsController','get', true );
        $this->addRoute('(?<controller>settings)/(?<action>nback)/?' , 'SettingsController','get', true );
        $this->addRoute('(?<controller>settings)/(?<action>personalUpdate)/?' , 'SettingsController','POST', true );
        $this->addRoute('(?<controller>settings)/(?<action>passwordUpdate)/?' , 'SettingsController','POST', true );
               
        $this->addRoute('(?<controller>authenticate)/?' , 'AuthenticateController','GET', false );

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

    private function addRoute( string $pattern, string $controller, string $method, $logged = false ): void
    {
        $method = strtoupper($method);

        $this->routes[$method]["`^{$pattern}$`"] = [ 
            'controller'    => $controller,
            'logged'        => $logged
        ];
    }
}