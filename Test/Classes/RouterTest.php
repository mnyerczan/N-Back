#!/usr/bin/php7.4
<?php

use App\Http\Router;

require "Test/init.php";


$route = new Router();

// A createPattern metódus privát! Át kell állítani
test(preg_match($route->createPattern(""), "/nback") == true);
test(preg_match($route->createPattern("/"), "/nback") == false);
test(preg_match($route->createPattern("/signUp/form"), "/signUp/form") == true);
test(preg_match($route->createPattern("/signIn"), "/signIn")== true);
test(preg_match($route->createPattern("/logUot/user/23"), "/logUot/user/23") == true);
test(preg_match($route->createPattern("/settings/passwordUpdate/12"), "/settings/passwordUpdate/12") == true);
test(preg_match($route->createPattern("/settings/passwordUpdate/12/alma"), "/settings/passwordUpdate/12/alma") == false);