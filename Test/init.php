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