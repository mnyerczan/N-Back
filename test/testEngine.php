#!/usr/bin/php7.4
<?php


error_reporting(1);

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

chdir("/var/www/html");

// Autoloader
spl_autoload_register(function($className) {
    $path = "";
    $units = explode("\\", $className);
    for($i = 0; $i < count($units); $i++) {
        if ($i < count($units) -1 ) {
            $path .= strtolower($units[$i][0]).substr($units[$i], 1);        
            $path .= DIRECTORY_SEPARATOR;            
        }        
        else
            $path .= $units[$i];
     }        
     
    include_once 'NBack/' . $path . '.php';
});