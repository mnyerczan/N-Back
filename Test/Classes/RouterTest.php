#!/usr/bin/php7.4
<?php

use App\Http\Router;

require "Test/testEngine.php";



class A extends Router
{
    public function cPattern(string $str, string $url): string
    {        
        return preg_match(parent::createPattern($str), $url);        
    }

    public static function createPattern(string $str): string
    {
        return parent::createPattern($str);
    }

    public function lRoutes(array $route, array $expect): bool
    {
        return parent::loadRoutes($route) == $expect;
    }
}

// Különleges esetek
// 1 üres url
test(new A, "cPattern", ["", "/nback"], false);
// 2 CSak egy per jel
test(new A, "cPattern", ["/", "/nback"], false);
// controller és opcionális action egyezés action
test(new A, "createPattern", ["/signUp/form"], "%^/(?<controller>signUp)/(?<action>form)/?$%");
// Csak kontroller egyezés
test(new A, "createPattern", ["/signIn"], "%^/(?<controller>signIn)/?$%");
// kontroller, action és id egyezés
test(new A, "cPattern", ["/logUot/user/23", "/logUot/user/23"], true);
// itt bevezetünk egy nem definiált részt az id után. Hamissal kell válaszolnia., mert az 
// utolsó tagot "alma" már levágja.
// "%^/(?<controller>settings)/(?<action>passwordUpdate)/(?<id>12)$%"
test(new A, "cPattern", ["/settings/passwordUpdate/12/alma", "/settings/passwordUpdate/12/alma"], false);



test(new A, "lRoutes", [[],[]], true);
test(new A, "lRoutes", [["get" => Null], []], true);
test(new A, "lRoutes", [["get" => ["" => []]], []], true);

test(new A , "lRoutes" , [[
                "get" => [
                    "/alma" => ["controller" => "test", "action" => NULL,"logged" => NULL]
                ]
            ],
            [
                "GET" => [
                        "%^/(?<controller>alma)/?$%" => ["controller" => "test", "action" => "index","logged" => false]
                ]
            ]
        ], true);

test(new A, "lRoutes", [[
    "get" => [
        "/" => [
            "controller" => "test", 
            "action" => NULL,
            "logged" => NULL
        ]            
    ]
        ],[
    "GET" => [
        "%^/?$%" => [
            "controller" => "test", 
            "action" => "index",
            "logged" => false
        ]
    ]
]], true);

test(new A, "lRoutes", [[
    "get" => [
        "/alma/cont/12" => [
            "controller" => "test", 
            "action" => NULL,
            "logged" => NULL
        ]            
    ]
        ],[
    "GET" => [
        "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
            "controller" => "test", 
            "action" => "index",
            "logged" => false
        ]
    ]
]], true);



test(new A, "lRoutes", [    [
    "get" => [
        "alma/cont/12" => [
            "controller" => "test", 
            "action" => NULL,
            "logged" => NULL
        ]            
    ]
        ],[
    "GET" => [
        "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
            "controller" => "test", 
            "action" => "index",
            "logged" => false
        ]
    ]
]], true);

test(new A, "lRoutes", [[
        "get" => [
            "alma/cont/12" => [
                "controller" => NULL, 
                "action" => NULL,
                "logged" => NULL
            ]            
        ]
    ],[
        "GET" => [
            "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
                "controller" => "App\Controller\HomeController", 
                "action" => "index",
                "logged" => false
            ]
        ]
    ]], true);


test(new A, "lRoutes", [[
      "get" => []
        ], [
            "GET" => [
                "%^/(?<controller>alma)/(?<action>cont)/(?<id>12)/?$%" => [
                    "controller" => "App\Controller\HomeController", 
                    "action" => "index",
                    "logged" => false
                ]
            ]
        ]], FALSE);