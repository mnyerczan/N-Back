<?php

namespace App\Http;


use App\Controller\NotFoundController;



final class Router
{          
    
    /**
     * Routes
     */
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
    
    
    private function loadRoutes()
    {
        $source = require "App/Http/routes.php";

        foreach($source as $httpMethod => $routes) {
            foreach($routes as $url => $route) {
                $pattern = $this->createPattern($url);
                $this->routes[$httpMethod]["{$pattern}"] = [
                    "controller" => $route["controller"],
                    "action" => $route["action"] ? $route["action"] : "index",
                    "logged" => $route["logged"] ? $route["logged"] : false,
                ];
            }
        }
    }

    /**
     * Mintakészítő function: tesztelve -> Test/Classes/RouterTest.php
     * 
     * A várt minta controller/action/id
     * @param string $url
     * @return string $pattern
     */
    private function createPattern(string $path): string
    {
        $pattern = "%^";
        $classes = [
            "?<controller>",
            "?<action>",
            "?<id>"
        ];
        if (0 < strlen($path) && $path[0] !== "/" || strlen($path) == 0)
            $path = "/".$path;
        $elements = explode("/", $path);
        unset($elements[0]);
        for($i=0; $i<count($elements); $i++) {
            if ($elements[$i+1] != "")
                $pattern.= "/(".$classes[$i].$elements[$i+1].")";
            else 
                $pattern.= "/";
            if ($i == count($elements) -1 && $pattern != "%/") 
                $pattern.="/?";            
        }
        return $pattern."$%";
    }
}