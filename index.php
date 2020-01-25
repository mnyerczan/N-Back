<?php 	
	define('APPLICATION', 'Application/');

	require_once APPLICATION.'application.php';

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
	ini_set("display_errors", 1);


/**
 * Set error logging
 */
	ini_set('error_log', 'log/error.log');
	ini_set("log_errors", 1);



	//ob_start();
	session_start();



# php SESSION_ID lifetime 24p.
	/* if($user->id > 1) 
		header("refresh: 1440; url=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"); */

/**
 * initialize log files
 */
	//require_once APPLICATION.'log/logHandler.php';


	#Ezekhez a változókhoz szükség van a $_SESSION változóira.
	//require_once("imp_var.php");
	
	
	


	//$main_theme = $user->theme ? $user->theme : (@$_cookie_datas['theme'] ? $_cookie_datas['theme'] : 'white');
	

/**
 * Set body arguments
 */
	/* $bodyParams = "";

	if( isset($_GET['nb_common']) && $_GET['nb_common'] == 1)
	{
		$bodyParams .= "onload='Session();'";
	}			
	$bodyParams .= ' onkeydown="key_event(event)" ';

	if($main_theme == 'white' ) 
		$bodyParams .= ' style="color:black;';
	if(strpos($_SERVER["HTTP_USER_AGENT"], "Chrome") || strpos($_SERVER["HTTP_USER_AGENT"], "Chromium"))
		$bodyParams .= "font-weight: bold;";
	
	$bodyParams .= '"'; */

	(new Application())->route();		