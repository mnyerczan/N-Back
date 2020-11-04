<?php   

// 
//    PHP-7.4, OR NEWER VERSION!!
//


declare(strict_types=1);


// Hibaüzenetek tartalmának futásidejű beállítása
error_reporting(E_ALL);

// Hibaüzenetek kiírásának futásidejű engedélyezése.
ini_set("display_errors", "1");


define('URI', explode('?', $_SERVER['REQUEST_URI'])[0]);    
// Böngésző cash kezelő konstans.    
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

// remove application directory from path    
$cleanedUri = str_replace( APPROOT,'', URI);    

// Cause the explode function cut to two part a string with a slash, and
// we need it works if the url contains two slash, we substract 2 from the result.
$num = count(explode('/',$cleanedUri )) -2; 

$backFromCurrentPath = '';

// A számlálás 1-től indul, mert az explode a /Thesis_v.2.0/error stringet 3 részre szeleteli.
// Ha projektmappát használunk, akkor a $i-nek 1-től kell indulnia!! Gyökérből 0-tól.
for ( $i = 0; $i < $num; $i++ )
{
    $backFromCurrentPath.= '../';
}  

define('BACKSTEP', $backFromCurrentPath);





header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: close");



/**
 * Set error logging
 */
ini_set('error_log', APPLICATION.'Log/error.log');
ini_set("log_errors", "0");

// Autoloader
spl_autoload_register(function($className) {
    // $classNema for debug...
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);    
    include_once $_SERVER['DOCUMENT_ROOT'] . '/NBack/' . $className . '.php';
});



App\Http\Router::route($cleanedUri);		