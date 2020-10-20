<?php

use DB\EntityGateway;
use Login\UserEntity;

require_once APPLICATION."functions.php";

final class Application
{               
    private array $routes;

    function __construct()
    {
        session_start();       
    }

    function route($cleanedUri): void
    {                                
  
        $this->addRoute('/?(?<controller>)/?', 'HomeController', 'get');    
             
        
        // SignUpController
        $this->addRoute('/?(?<controller>signUp)/(?<action>form)/?' , 'SignUpController' , 'get');                
        $this->addRoute('/?(?<controller>signUp)/(?<action>submit)/?' , 'SignUpController', 'POST', 'submit');        
        
        // SignInController
        $this->addRoute('/?(?<controller>signIn)/?','SignInController' , 'get');
        $this->addRoute('/?(?<controller>signIn)/(?<action>submit)' , 'SignInController', 'POST', 'submit');

        // LogUotController
        $this->addRoute('/?(?<controller>logUot)','LogUotController' , 'get');
        
        
        $this->addRoute('/?(?<controller>account)/?','AccountController','get',  'index',true );
        
        // Settings (account)
        $this->addRoute('/?(?<controller>settings)/?','SettingsAccountController','get','index', true );                 
        $this->addRoute('/?(?<controller>settings)/(?<action>personalUpdate)/?' , 'SettingsAccountController','POST','personalUpdate', true );
        $this->addRoute('/?(?<controller>settings)/(?<action>passwordUpdate)/?' , 'SettingsAccountController','POST','passwordUpdate', true );
        $this->addRoute('/?(?<controller>settings)/(?<action>imageUpdate)/?' , 'SettingsAccountController','POST','imageUpdate', true );

        

        // Settings (nback)
        $this->addRoute('/?(?<controller>settings)/(?<action>nback)/?' , 'SettingsNbackController','GET', 'index', true );
        $this->addRoute('/?(?<controller>settings)/(?<action>nback)/?' , 'SettingsNbackController','POST', 'update', true );

        // API 
        $this->addRoute('/?api/(?<controller>authenticate)/?' , 'AuthenticateController','GET','index', false );

        // NBACK
        $this->addRoute('/?(?<controller>nBack)/?', 'nBackController','get' );        

        // Documents
        $this->addRoute('/?(?<controller>documents)/?' , 'documentsController','get' );
        


        // A $routes tömb minden metódushoz egy "regex minta":[paraméter tömb] típusú adatszerkezetet vesz fel.
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $pattern => $params)        
        {     

            if (preg_match( $pattern, $cleanedUri, $matches ))
            {   

                // Fejlesztendő!!!
                // Ha szükséges bejelentkezés az adott view megtekintéséhez, de már lejárt a session, ne
                // 404-et dobjon, hanem irányítson át!!
                if ($params['logged'] && isset($_SESSION['userId']) || !$params['logged'])
                {                                     
                    
                    require_once APPLICATION."Controllers/{$params['controller']}.php";                    


                    // A php nem enged közvetlenül tömbből kiolvasott értéket hívni, ezért le kell menteni?!!...
                    $action = $params['action'];

                                               

                    // Controller hívása a kapott metódussal.
                    (new $params['controller']($matches))->$action();
                    return;
                }               
            }
        }        
        new NotFoundController();
                        
    }    

    /**
     * @param $pattern      Az url illesztési mintája,
     * @param $contoller    Az url-hez tartozó kontroller,
     * @param $action       Az meghívandó függvény
     * @param $httpMethod   A kontrollert meghívható HTTP metódus,
     * @param $logged       A kliens be van-e jelentkezve, későbbi ellenőrzéshez.
     * 
     * @return void
     */
    private function addRoute( 
        string  $pattern, 
        string  $controller, 
        string  $httpMethod, 
        string  $action = 'index', 
        bool    $logged = false ): void
    {
        $method = strtoupper($httpMethod);



        $this->routes[$method]["`^{$pattern}$`"] = [ 
            'controller'    => $controller,
            'logged'        => $logged,
            'action'        => $action
        ];
    }
}