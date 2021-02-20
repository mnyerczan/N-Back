<?php

declare(strict_types = 1);
// error_reporting(1);

// Back to the app root
chdir("../");



// Hibaüzenetek tartalmának futásidejű beállítása
error_reporting(E_ALL);

// Hibaüzenetek kiírásának futásidejű engedélyezése.
ini_set("display_errors", "1");



$appConfig = require "config/appConfig.php";

require "app/functions.php";


// Root directory. Must be at least '/' !
define("APPROOT", $appConfig["applicationRoot"]);

define("FSROOT", getcwd()."/");

define("DBCONFIGPATH", $appConfig["dbConfigPath"]);

define("APPLICATION", "app/");
// Temp mappa útvonal
define('TMP_PATH', APPLICATION.'Tmp/');
// HTTP protocol
define('HTTP_PROTOCOL', 'http://');

define("RELOAD_INDICATOR", $appConfig["reloadIndicator"]);
// remove application directory from path    
define("CLEANED_URI", str_replace( APPROOT,'', explode('?', $appConfig["cwd"])[0]));
// Define backstep for correct url to download sources from public/ folder..
define('BACKSTEP', calcBackStep(CLEANED_URI));

require_once "app/controller/errors/ExceptionErrorController.php";
require_once "app/core/core.php";