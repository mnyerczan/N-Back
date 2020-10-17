<?php

chdir('/var/www/html/NBack/');

// Böngésző cash kezelő konstans.
#define('RELOAD_CONTROLLER'	, '' );
define('RELOAD_CONTROLLER'	, date('s') );
// Program mappa
define('APPLICATION'		, 'Application/');
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



require_once APPLICATION.'functions.php';
require_once APPLICATION.'Interfaces/DBInterface.php';
require_once APPLICATION.'DB/DB.php';
require_once APPLICATION.'DB/EntityGateway.php';    
require_once APPLICATION.'Models/Home/homeViewModel.php';
require_once APPLICATION.'Models/userEntity.php';
require_once APPLICATION.'Models/sessions.php';
require_once APPLICATION.'Models/seria.php';
require_once APPLICATION.'Models/home.php';
require_once APPLICATION.'Models/navbar.php';
require_once APPLICATION.'Models/indicator.php';
require_once APPLICATION.'Models/header.php';    
require_once APPLICATION.'Models/menus.php';
require_once APPLICATION.'Models/ModelAndView.php';
require_once APPLICATION.'Classes/ImageConverter.php';
require_once APPLICATION.'Classes/validator.php';
require_once APPLICATION.'Classes/validateEmail.php';
require_once APPLICATION.'Classes/validateUser.php';
require_once APPLICATION.'Classes/validateDate.php';
require_once APPLICATION.'Classes/validateSex.php';
require_once APPLICATION.'Classes/ValidateAbout.php';
require_once APPLICATION.'Classes/validatePassword.php';
require_once APPLICATION."Classes/JsonRenderer.php";
require_once APPLICATION.'Core/ResponseFactory.php';
require_once APPLICATION.'Core/ResponseEmitter.php';
require_once APPLICATION.'Core/Response.php';
require_once APPLICATION.'Core/ViewRenderer.php';
require_once APPLICATION."Core/BaseController.php";
require_once APPLICATION."Core/MainController.php";
require_once APPLICATION."Core/GameController.php";    
require_once APPLICATION."Controllers/NotFoundController.php";
require_once APPLICATION."Controllers/AuthenticateController.php";