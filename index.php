<?php 

	use DB\MySql;
	use DB\DBInterface;
	use DB\EntityGateway;
	use Login\UserEntity;

	require_once 'Class/userEntity.php';
	require_once 'Interfaces/DBInterface.php';
	require_once 'DB/MySql.php';
	require_once 'DB/entityGateway.php';

    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	header("Connection: close");
	
	require_once("globals.php");
	require_once("connect.php");
	require_once("special_characters_handler.php");
	require_once("functions.php");

	

/**
 * Hibaüzenetek tartalmának futásidejű beállítása
 */
	error_reporting(E_ALL);

/**
 * Hibaüzenetek kiírásának futásidejű engedélyezése.
 */
	ini_set("display_errors", 1);

/**
 * Create user object
 */
	$user = new UserEntity( EntityGateway::getDB() );

/**
 * Set error logging
 */
	ini_set('error_log', 'log/error.log');
	ini_set("log_errors", 1);



	ob_start();
	session_start();

	if(isset($_GET['exit']))
	{				
		$result = ( EntityGateway::getDB() )->Select("UPDATE users SET `online` = 0 WHERE u_name = :name ", [':name' => $_SESSION['u_name']]);

		if( !$result && @$_COOKIE[session_name()])
		{
			setcookie(session_name(), '', time()-42000, '/');
		}

		header("Location: index.php?");
		die;
	}
	else
	{				
		if( @$_SESSION['u_name'] )
		{			
			$user->Load( $_SESSION['u_name'], $_SESSION['password'] );
		}					
	}



# php SESSION_ID lifetime 24p.
	if($user->id > 1) 
		header("refresh: 1440; url=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");



/**
 * initialize log files
 */
	require_once 'log/logHandler.php';


	#Ezekhez a változókhoz szükség van a $_SESSION változóira.
	require_once("imp_var.php");
	
	
	


	$main_theme = $user->theme ? $user->theme : (@$_cookie_datas['theme'] ? $_cookie_datas['theme'] : 'white');
	

/**
 * Set body arguments
 */
	$bodyParams = "";

	if( isset($_GET['nb_common']) && $_GET['nb_common'] == 1)
	{
		$bodyParams .= "onload='Session();'";
	}			
	$bodyParams .= ' onkeydown="key_event(event)" ';

	if($main_theme == 'white' ) 
		$bodyParams .= ' style="color:black;';
	if(strpos($_SERVER["HTTP_USER_AGENT"], "Chrome") || strpos($_SERVER["HTTP_USER_AGENT"], "Chromium"))
		$bodyParams .= "font-weight: bold;";
	
	$bodyParams .= '"';
/**
 * Page
 */
	$page = $_GET['index'] ?? - 1;

	require_once 'Application/view/_layout.php';

