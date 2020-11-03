#!/usr/bin/php7.4
<?php

use App\Http\Route;

require "Test/init.php";


$route = new Route();


echo (preg_match($route->createPattern(""), "/nback") == false ? 1 : 0)." -> /nback, \"".$route->createPattern("")."\": ".PHP_EOL;
echo (preg_match($route->createPattern("/"), "/nback") == false ? 1 : 0)." -> /nback, \"".$route->createPattern("/")."\": ".PHP_EOL;
echo (preg_match($route->createPattern("/signUp/form"), "/signUp/form") == true ? 1 : 0)." -> /signUp/form, \"".$route->createPattern("/signUp/form")."\": ".PHP_EOL;
echo (preg_match($route->createPattern("/signIn"), "/signIn")== true ? 1 : 0)." -> /signIn, \"".$route->createPattern("/signIn")."\": ".PHP_EOL;
echo (preg_match($route->createPattern("/logUot/user/23"), "/logUot/user/23") == true ? 1 : 0)." -> /logUot/user/23, \"".$route->createPattern("/logUot/user/23")."\": ".PHP_EOL;
echo (preg_match($route->createPattern("/settings/passwordUpdate/12"), "/settings/passwordUpdate/12") == true ? 1 : 0)." -> /settings/passwordUpdate/12, \"".$route->createPattern("/settings/passwordUpdate/12")."\": ".PHP_EOL;

