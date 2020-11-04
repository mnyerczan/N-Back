#!/usr/bin/php7.4
<?php

use App\Http\Router;

require "Test/init.php";



class A extends Router
{
    public static function createPattern(string $str): string{        
        return parent::createPattern($str);
    }

    public static function loadRoutes(array $route): array{        
        return parent::loadRoutes($route);
    }
}
echo "createPattern".PHP_EOL;
test(preg_match(A::createPattern(""), "/nback") == false);
test(preg_match(A::createPattern("/"), "/nback") == false);
test(preg_match(A::createPattern("/signUp/form"), "/signUp/form") == true);
test(preg_match(A::createPattern("/signIn"), "/signIn")== true);
test(preg_match(A::createPattern("/logUot/user/23"), "/logUot/user/23") == true);
test(preg_match(A::createPattern("/settings/passwordUpdate/12"), "/settings/passwordUpdate/12") == true);
test(preg_match(A::createPattern("/settings/passwordUpdate/12/alma"), "/settings/passwordUpdate/12/alma") == false);

echo "loadRoutes".PHP_EOL;
test(A::loadRoutes(
    [
        "get" => [
            "/alma" => [
                "controller" => "test", 
                "action" => NULL,
                "logged" => NULL
            ]            
        ]
    ]) == [
        "GET" => [
            "%^/(?<controller>alma)/?$%" => [
                "controller" => "test", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]);

test(A::loadRoutes(
    [
        "get" => [
            "/" => [
                "controller" => "test", 
                "action" => NULL,
                "logged" => NULL
            ]            
        ]
    ]) == [
        "GET" => [
            "%^/?$%" => [
                "controller" => "test", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]);

test(A::loadRoutes(
    [
        "get" => [
            "/alma/cont/12" => [
                "controller" => "test", 
                "action" => NULL,
                "logged" => NULL
            ]            
        ]
    ]) == [
        "GET" => [
            "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
                "controller" => "test", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]);

test(A::loadRoutes(
    [
        "get" => [
            "alma/cont/12" => [
                "controller" => "test", 
                "action" => NULL,
                "logged" => NULL
            ]            
        ]
    ]) == [
        "GET" => [
            "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
                "controller" => "test", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]);


test(A::loadRoutes(
    [
        "get" => [
            "alma/cont/12" => [
                "controller" => NULL, 
                "action" => NULL,
                "logged" => NULL
            ]            
        ]
    ]) == [
        "GET" => [
            "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
                "controller" => "App\Controller\HomeController", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]);

test(A::loadRoutes(
    [
        "get" => []
    ]) !== [
        "GET" => [
            "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
                "controller" => "App\Controller\HomeController", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]);

test(A::loadRoutes(["get" => Null]) == []);
test(A::loadRoutes(["get" => ["" => []]]) == []);