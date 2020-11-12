<?php

declare(strict_types = 1);
// error_reporting(1);

// Back to the app root
chdir("../");


$appConfig = require "config/appConfig.php";

require "app/functions.php";


// Root directory. Must be at least '/' !
define("APPROOT", $appConfig["applicationRoot"]);

define("DBCONFIGPATH", $appConfig["dbConfigPath"]);

define("APPLICATION", "app/");

define("RELOAD_INDICATOR", $appConfig["reloadIndicator"]);

// remove application directory from path    
define("CLEANED_URI", str_replace( APPROOT,'', explode('?', $appConfig["cwd"])[0]));

// Define backstep for correct url to download sources from public/ folder..
define('BACKSTEP', calcBackStep(CLEANED_URI));


require "app/core/core.php";