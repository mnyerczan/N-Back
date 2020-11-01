<?php

namespace App\Core;

use App\Controller\NotFoundController;

require_once APPLICATION."functions.php";

final class Application
{               
    private array $routes;


    function route($cleanedUri): void
    {
        // Ha nincs jogosultság elérni a /var/lib/apache2/sessions/... fájlt, error view.
        if(@!session_start()) {
            // $this->addRoute('/(?<controller>sessionError)/?', "App\Controller\SessionError", 'get');   
            $cleanedUri = "/sessionError";
        }        
        
        $this->loadRoutes();

        // A $routes tömb minden metódushoz egy "regex minta":[paraméter tömb] típusú adatszerkezetet vesz fel.
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $pattern => $params) {
            if (preg_match( $pattern, $cleanedUri, $matches )) {
                // Fejlesztendő!!!
                // Ha szükséges bejelentkezés az adott view megtekintéséhez, de már lejárt a session, ne
                // 404-et dobjon, hanem irányítson át!!
                if ($params['logged'] && isset($_SESSION['userId']) || !$params['logged']) {                            

                    // A php nem enged közvetlenül tömbből kiolvasott értéket hívni, ezért le kell menteni?!!...
                    $action = $params['action'];                    

                    // Controller hívása a kapott metódussal.
                    (new $params['controller']($matches))->$action();
                    return;
                }               
            }
        }
        (new NotFoundController())->action();
    }    



    private function loadRoutes() {
        $routes = require "App/Core/Http/routes.php";

        foreach ($routes as $httpMethod => $routes) {
            foreach ( $routes as $pattern => $datas)
                $this->addRoute(
                    "{$pattern}",
                    $datas["controller"],
                    $httpMethod ,
                    $datas["action"] ? $datas["action"] : "index",
                    $datas["logged"] ? $datas["logged"] : false
                );
        }
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
        string  $action, 
        bool    $logged ): void
    {        

        $this->routes[strtoupper($httpMethod)]["`^{$pattern}$`"] = [ 
            'controller'    => $controller,
            'logged'        => $logged,
            'action'        => $action
        ];
    }

}