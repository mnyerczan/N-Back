<?php 	
	define('URI', explode('?', $_SERVER['REQUEST_URI'])[0]);

	$num = count(explode( '/' , URI ) ) - 2;
	$backFromCurrentPath = '';
	/**
	 * A számlálás 1-től indul, mert az explode a /Thesis_v.2.0/error stringet 3 részre szeleteli.
	 */
	for ( $i = 1; $i < $num; $i++ )
	{
		$backFromCurrentPath.= '../';
	}  

	define('RELPATH', $backFromCurrentPath);

	define('APPLICATION', 'Application/');


	define('APPROOT', '/Thesis_v.2.0/');
	//define('APPROOT', '/');

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
	ini_set('error_log', 'Log/error.log');
	ini_set("log_errors", 1);



	//ob_start();




# php SESSION_ID lifetime 24p.
	/* if($user->id > 1) 
		header("refresh: 1440; url=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"); */


	(new Application())->route();		