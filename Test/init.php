<?php

chdir('/var/www/html/NBack/');

// Böngésző cash kezelő konstans.
#define('RELOAD_INDICATOR'	, '' );
define('RELOAD_INDICATOR'	, date('s') );
// Program mappa
define('APPLICATION'		, 'App/');
// Root directory. Must be at least '/' !
define('APPROOT'			, "/NBack");
// Temp mappa útvonal
define('TMP_PATH'			, APPLICATION.'Tmp/');
// Config path
define('CONF_PATH'			, 'config.json');
// HTTP protocol
define('HTTP_PROTOCOL'      , 'http://');
define('BACKSTEP', '');

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';



// Autoloader
spl_autoload_register(function($className) {
    // $classNema for debug...
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);    
    include_once $_SERVER['DOCUMENT_ROOT'] . $className . '.php';;
});


function test(bool $stmt)
{
    echo "At: ".debug_backtrace()[0]["line"]." -> ";
    if ($stmt) {
        echo "\e[1;37;47mtrue\e[0m";
    }
    else
        echo "\e[1;37;41mfalse\e[0m";
    echo PHP_EOL;
//    echo "\e[0;31;42mMerry Christmas!\e[0m\n";

}