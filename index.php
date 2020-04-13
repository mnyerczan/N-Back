<?php

    declare(strict_types=1);
        
    //$response = new Response('{"body":"22"}', ["content-type" => "application/json"], 200, "Ok");
    //$emitter  = new ResponseEmitter();
    //$emitter->emit($response);
    //die;


    define('URI', explode('?', $_SERVER['REQUEST_URI'])[0]);

	//Aktuális idő konstans
	define('CURRENT_TIMESTAMP'	, date('s') );

	// Program mappa
	define('APPLICATION'		, 'Application/');

	// Program gyökér mappa
	define('APPROOT'			, '/');
	
	// Temp file útvonal
	define('TMP_PATH'			, APPLICATION.'Tmp/');



	$num = count(explode( '/' , URI ) ) - 2;
	$backFromCurrentPath = '';

/**
 * A számlálás 1-től indul, mert az explode a /Thesis_v.2.0/error stringet 3 részre szeleteli.
 * Ha projektmappát használunk, akkor a $i-nek 1-től kell indulnia!! Gyökérből 0-tól.
 */
	for ( $i = 0; $i < $num; $i++ )
	{
		$backFromCurrentPath.= '../';
	}  

	define('RELPATH', $backFromCurrentPath);

	

	require_once APPLICATION.'Core/application.php';

	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	header("Connection: close"); 
	

/**
 * Hibaüzenetek tartalmának futásidejű beállítása
 */
	error_reporting(E_ALL);

/**
 * Hibaüzenetek kiírásának futásidejű engedélyezése.
 */
	ini_set("display_errors", "1");


/**
 * Set error logging
 */
	ini_set('error_log', APPLICATION.'Log/error.log');
	ini_set("log_errors", "0");


/**
 * Ennek a függvénynek a hatása, hogy a kimenetet nem írja bele egyből a HTTP üzenettőrzsbe, 
 * hanem be- "kesseli", és csak az ob_end() után írja bele. A fejléctartalma kiírásakor lehet hasznos.
 */
	ob_start();




# php SESSION_ID lifetime 24p.
	/* if($user->id > 1) 
		header("refresh: 1440; url=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"); */
                            

    require_once APPLICATION.'Interfaces/DBInterface.php';
    require_once APPLICATION.'DB/MySql.php';
    require_once APPLICATION.'DB/EntityGateway.php';
    require_once APPLICATION.'Models/Image/ImageConverter.php';
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
    require_once APPLICATION.'Models/Validators/validator.php';
    require_once APPLICATION.'Models/Validators/validateEmail.php';
    require_once APPLICATION.'Models/Validators/validateUser.php';
    require_once APPLICATION.'Models/Validators/validateDate.php';
    require_once APPLICATION.'Models/Validators/validatePassword.php';
    require_once APPLICATION.'Core/ResponseFactory.php';
    require_once APPLICATION.'Core/ResponseEmitter.php';
    require_once APPLICATION.'Core/Response.php';
    require_once APPLICATION.'Core/ViewRenderer.php';
    require_once APPLICATION."Core/MainController.php";
    require_once APPLICATION."Controllers/NotFoundController.php";
    



	(new Application())->route();		