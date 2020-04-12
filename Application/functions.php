<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 	$_GET assoc tömb kulcs - érték párjainak url-be illeszthető stringé alakításáért felelős függvény			 //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Get_get_datas_to_sting(){

	GLOBAL 	$_get_datas,
			$error_level,
			$logfile,
			$log_param_1;

	$exit = null;
	$string = '';

	if($_get_datas != ''){

		foreach( $_GET as $key=>$value){
			$string .= $key.'='.$value.'&';
		}
		$exit = substr($string, 0 , -1);
	}

	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 	??? Fórum ???		Lehet egy file-ban kellene kezelni az adatbázis müveleteket?!							  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Delete_user(){

	global 	$_SESSION,
			$userID,
			$img_name,
			$log_param_1,
			$logfile,
			$now,
			$error_level;

	$exit = 1;
	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Delete_user] ***SART***\n", $log_param_1);

	if($error_level > 1)file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Delete_user] user ID: '.$userID."\n", $log_param_1);

	if($userID != 'none' ){
		$remove_user_sql = '
			DELETE FROM users
			WHERE
				id = "'.$userID.'";';
		if($img_name != 'none'){
			Delete_image_from_server();
		}

		$exit = Sql_execute_query($remove_user_sql);

		if($userID == $_SESSION['user_datas']['id']){
			session_destroy();
		}
	}
	if($error_level > 1) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Delete_user] SQL: '.$remove_user_sql. "\n", $log_param_1);
	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Delete_user] EXIT CODE: '.$exit. "\n", $log_param_1);
	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Delete_user] *** END ***\n", $log_param_1);
	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//#	Kép törlése függvény	 				 																	  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Delete_image_from_server(){

	global 	$_SESSION,
			$userID,
			$img_name,
			$log_param_1,
			$logfile,
			$now,
			$error_level;
	$exit = 1;

	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] *** START ***\n", $log_param_1);


	if($userID != '' && $userID != 'none'){
		$img_name = Sql_query('
			SELECT
				fileName
			FROM users
			WHERE
				id = '.$userID.';')[0]['fileName'];


		$sql_remove_image = '
			UPDATE users
			SET
				fileName = "none"
			where
				id = '.$userID.';';

		if(Sql_execute_query($sql_remove_image) == 0 ){

			if(file_exists('users/'.$userID. '/' . $img_name))	$exit = unlink('users/'.$userID. '/' . $img_name);
			elseif($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: Profile image not exists\n", $log_param_1);

			if(file_exists('users/forum_images/' . $img_name)) $exit = unlink('users/forum_images/' . $img_name);
			elseif($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: Forum image not exists\n", $log_param_1);

			if(is_dir('users'.'/'.$userID)) rmdir('users'.'/'.$userID);
			elseif($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: users/".$userID." Directory not exists\n", $log_param_1);

		}

		if(isset($_SESSION['user_datas']['id']) && ($_SESSION['user_datas']['id'] != null || $_SESSION['user_datas']['id'] != "") )Load_datas();

	}
	else{
		if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: Dont get user's ID\n", $log_param_1);
	}
	if($error_level > 0) 	 file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] *** END ***\n", $log_param_1);

	return $exit == TRUE ? 0 : 1;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//#	PDO lekérdezés	 				 																		      //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Sql_query($sql){

	GLOBAL  $conn_pdo,
			$log_param_1,
			$logfile,
			$now,
			$error_level;
	
	$proto_result = [];

	try
	{
		
		$query=$conn_pdo->query($sql);
		$proto_result = $query->fetchAll();
		
 		if($error_level >3) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'] - [SQL_QUERY] OBJECT: '.print_r(json_decode(json_encode($proto_result)), true)."\n"
 													   ."[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'] - [SQL_QUERY] SQL: '.$sql."\n\n", $log_param_1);
		if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."] - [SQL_QUERY] OK\n" , $log_param_1);
	}
	catch(PDOException  $e )
	{
		if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'] - [SQL_QUERY] Error: '.$e->getMessage()."\n"
														."[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'] - [SQL_QUERY] SQL: '.$sql."\n", $log_param_1);
		return false;
	}

	
	return $proto_result;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Módisító lekérdezés, PDO objektumot használ	 																  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Sql_execute_query($sql){

	GLOBAL	$conn_pdo,
			$log_param_1,
			$logfile,
			$now,
			$error_level;
	
	try
	{
		$conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if(!$conn_pdo->exec($sql))
		{									
			throw new PDOException();
		}		
		if($error_level >0) 
			file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."] - [SQL_EXECUTE_QUERY] OK \n", $log_param_1);
		$exit = 0;
	}
	catch(PDOException $e)
	{
		if($error_level >0) 
			file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."] - [SQL_EXECUTE_QUERY] - Error Code:  ".$e->getMessage()."\n",$log_param_1);
		$exit = 1;
	}
	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Bejelentkezést végző függvény	  																			  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Load_datas(){

	GLOBAL 	$_post_datas,
			$_SESSION,
			$conn,
			$_get_datas,
			$log_param_1,
			$logfile,
			$error_level,
			$userID,
			$now;
			$exit = 1;

	$pass = "none";
	$_post_pass = "none";

	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] ***START***'.\n", $log_param_1);


	#Adatmódosításnál
	if(isset($_post_datas['userName']) || isset($_post_datas['userName_original'])){

		if($_post_datas['userName'] != NULL || $_post_datas['userName'] != '') $login_name = $_post_datas['userName'];
		else $login_name = $_post_datas['userName_original'];

		if( isset($_post_datas['pass']) && strlen($_post_datas['pass']) < 4) $_post_pass = $pass = $_post_datas['pass_original'];
		else $pass = md5("salt".md5($_post_pass = $_post_datas['pass']));

		if($error_level >1) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] POST[pass]: ".(isset($_post_datas['pass'])?$_post_datas['pass']:'none')."\n".
														"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] POST[pass_original]: "."\n".
														"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] FINAL PASS: ".$_post_pass."\n",$log_param_1);

		$sql_nb="
				select
					n.id,
					n.gameMode,
					n.level,
					n.seconds,
					n.trials,
					n.eventLength,
					n.color,
					current_timestamp AS refresh
				from users u, nbackDatas n
				where u.id = n.userID
					and u.userName = '".Escape_special_characters($login_name)."'
					and u.password='".$pass."';";

		$sql_user="
				select * from users
				where
					userName='".Escape_special_characters($login_name)."'
					and password='".$pass."';";
	}
	#Egyébb: pl. témamódosítás után.
	elseif( $userID != 'none')
	{
		#módosítás: az értékadás után módosítja a $_SESSION tartalmát, így a lekérdezés eredménye zero.
		$_SESSION = $_SESSION;

		$sql_nb="select
					n.id,
					n.gameMode,
					n.level,
					n.seconds,
					n.trials,
					n.eventLength,
					n.color,
					current_timestamp AS refresh
				from users u, nbackDatas n
				where u.id = n.userID
					and u.id = '".$userID."'
					and u.password='".$_SESSION['user_datas']['password']."';";

		$sql_user="select * from users
				where userName='".$_SESSION['user_datas']['userName']."'
 				and password = '".$_SESSION['user_datas']['password']."';";
	}



	$result_user = Sql_query( $sql_user );
	$result_nb   = Sql_query( $sql_nb );

	if( 1 == count($result_user) && 1 == count($result_nb))
	{
		if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] OK\n", $log_param_1);

		$_SESSION['user_datas']['id'] 				= $result_user[0]['id'];
		$_SESSION['user_datas']['name']				= Include_special_characters($result_user[0]['name']);
		$_SESSION['user_datas']['email']			= Include_special_characters($result_user[0]['email']);
		$_SESSION['user_datas']['loginDatetime']	= $result_user[0]['loginDatetime'];
		$_SESSION['user_datas']['userName']			= Include_special_characters($result_user[0]['userName']);
		$_SESSION['user_datas']['privilege']		= $result_user[0]['privilege'];
		$_SESSION['user_datas']['password']			= $result_user[0]['password'];
		$_SESSION['user_datas']['birth']				= $result_user[0]['birth'];
		$_SESSION['user_datas']['passwordLength']		= $result_user[0]['passwordLength'];
		$_SESSION['user_datas']['fileName']		= $result_user[0]['fileName'];
		$_SESSION['user_datas']['theme']				= $result_user[0]['theme'];
		$_SESSION['user_datas']['online'] 			= $result_user[0]['online'];
		$_SESSION['user_datas']['refresh'] 			= $result_nb[0]['refresh'];


		$_SESSION['nbackDatas']['id'] 				= $result_nb[0]['id'];
		$_SESSION['nbackDatas']['gameMode'] 		= $result_nb[0]['gameMode'];
		$_SESSION['nbackDatas']['level'] 			= $result_nb[0]['level'];
		$_SESSION['nbackDatas']['seconds']		= $result_nb[0]['seconds'];
		$_SESSION['nbackDatas']['trials'] 		= $result_nb[0]['trials'];
		$_SESSION['nbackDatas']['eventLength'] = $result_nb[0]['eventLength'];
		$_SESSION['nbackDatas']['color'] 			= $result_nb[0]['color'];

		$exit = 0;
	}
	else
	{
			if($error_level >2) file_put_contents($logfile,"\n[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] RESULT_USER: ".print_r($result_user,true), $log_param_1);
			if($error_level >2) file_put_contents($logfile,"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] RESULT_NB_DATAS:".print_r($result_nb, true), $log_param_1);
			if($error_level >0) file_put_contents($logfile,"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] - Failed \n", $log_param_1);
		$exit =  1;

	}
	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][LOAD DATAS] ***END***'."\n", $log_param_1);
	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# N-Back Game datas upload		  	 																		  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Insert_nb_sessions(){

	GLOBAL	$_cookie_datas,
			$log_param_1,
			$logfile,
			$now,
			$error_level;


	$result = Genereate_sessions_result_column_value();

	$gameMode = (isset($_cookie_datas['gameMode']) && $_cookie_datas['gameMode'] == 'Position') ? 0 : 1;

	if(isset($_SESSION['user_datas']['id']))
	{

			$sql_insert_session = 'INSERT INTO nbackSessions (userID,ip,level,correctHit,wrongHit,sessionLength,gameMode, result) VALUES
				("'.$_SESSION['user_datas']['id'].'",
				"'.$_SERVER['REMOTE_ADDR'].'",
				"'.$_SESSION['nbackDatas']['level'].'",
				"'.$_cookie_datas['correctHit'].'",
				"'.$_cookie_datas['wrongHit'].'",
				"'.$_cookie_datas['sessionLength'].'",
				"'.$gameMode.'",
				"'.$result.'");';

	}
	else{
			$sql_insert_session='INSERT INTO nbackSessions (userID,ip,level,correctHit,wrongHit,sessionLength,gameMode, result) VALUES
					("1",
					"'.$_SERVER['REMOTE_ADDR'].'",
					"'.$_cookie_datas['level'].'",
					"'.$_cookie_datas['correctHit'].'",
					"'.$_cookie_datas['wrongHit'].'",
					"'.$_cookie_datas['sessionLength'].'",
					"'.$gameMode.'",
					"'.$result.'");';

		}
	
	if(isset($_cookie_datas['sessionUpload']) && $_cookie_datas['sessionUpload'] == '0' && Sql_execute_query($sql_insert_session) === 0)
	{
		if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Insert_nb_sessions] SQL : ", $sql_insert_session."\n", $log_param_1);
		setCookie('sessionUpload','1', time() * 60 * 60 *24 * 365, APPROOT);
		setCookie('lastModified',date("Y.m.d H:i:s "), time() * 60 * 60 *24 * 365, APPROOT);
		$exit = 0;

	}
	else
	{
		if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Insert_nb_sessions] SQL : ".$sql_insert_session."\n", $log_param_1);
		$exit = 1;

	}
	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Insert_nb_sessions] Exit code: '. $exit."\n", $log_param_1);
	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# 	 A név magáért beszél.																				  	  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Genereate_sessions_result_column_value(){

	GLOBAL 	$_SESSION,
			$_cookie_datas,
			$log_param_1,
			$logfile,
			$now,
			$error_level;

		if($_cookie_datas['gameMode'] == 'Position'){

			if($_cookie_datas['correctHit'] > 0 ){

				$pro_res = $_cookie_datas['correctHit'] / ($_cookie_datas['correctHit'] + $_cookie_datas['wrongHit']) * 100;
				$exit = ($pro_res >= 80 ) ? '1' : (($pro_res > 50 || $_cookie_datas['level'] == 1) ? '0' : '-1');

				if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][CREATE SESSION RESUL]][POSITION] RESULT: '.$exit."\n", $log_param_1);
				return $exit;
			}
			if($_cookie_datas['wrongHit'] > 0 &&  $_cookie_datas['level'] > 1){

				if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][CREATE SESSION RESUL][POSITION] RESuLT: -1\n", $log_param_1);
				return -1;
			}
		}
		if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][CREATE SESSION RESUL][gameMode] RESULT: 0\n", $log_param_1);
		return 0;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# 	Új felhasználó 																				  			  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Create_user_group(){

	GLOBAL $_post_datas,
			$conn,
			$_SESSION,
			$file,
			$log_param_1,
			$logfile,
			$now,
			$error_level;
	try{
		Load_file_to_server(Id_gen(false), $file);
   		$exit = Create_user($_post_datas, $file);

	}
	catch(Exception $e){
		$GLOBAL['e'] = $e;
		$exit = 5;
	}

	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Create_user_group] CREATE GROUP EXIT: '.$exit."\n", $log_param_1);
	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Felhasználói adatok módosítása	  																		      //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Modify_user_datas(){

	global $file,
			$_post_datas,
			$_SESSION,
			$conn_pdo,
			$userID,
			$log_param_1,
			$logfile,
			$now,
			$error_level;

	$exit = 1;

	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas] ***START***\n", $log_param_1);
	if($error_level > 2) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas][POST]".print_r($_POST, true)."\n", $log_param_1);

	if($userID != 'none'){

		$exit = Load_file_to_server($userID, $file);
		if($_post_datas['mail'] == null || $_post_datas['mail'] == '')	$mail= $_post_datas['mail_original'];
		else $mail= $_post_datas['mail'];

		if( $_post_datas['userName'] == null || $_post_datas['userName'] == '') $userName = $_post_datas['userName_original'];
		else $userName = $_post_datas['userName'];

		if($_post_datas['pass'] != null && $_post_datas['pass'] != '' && strlen($_post_datas['pass']) > 3){
			$pass = md5("salt".md5( $_post_datas['pass']));
			$_post_pass = strlen($_post_datas['pass']);
		}
		else {
			$pass = $_post_datas['pass_original'];
			$_post_pass = $_post_datas['passwordLength_original'];
		}

		$privilege = isset($_POST['privilege']) ? $_POST['privilege'] : $_SESSION['user_datas']['privilege'];
		$sql = "
		UPDATE users
		SET
			userName='".Escape_special_characters($userName)."',
			email='".Escape_special_characters($mail)."',
			password='".$pass."' ,
			passwordLength = '".$_post_pass."' ,
			privilege='".$privilege."'
		where
			id='".$userID."';";

		if (Sql_execute_query($sql) === 0){

			if($userID == $_SESSION['user_datas']['id']){
				$exit = Load_datas();
			}
		}
		else  $exit = 1;

		if($error_level > 0) file_put_contents($logfile, "[".
		$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas] Error Code:".$exit."\n[".
		$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas] SQL: ".$sql."\n[".
		$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas] ***END***\n", $log_param_1);
	}
	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//#	N-Back beállitásai mentése. Ha van user a $_SESSION változóban, a módosítások bekerülnek az adatbázisba.	  //
//	Ellenkező esetben a böngésző tárolja azokat sütiként.														  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Update_nback_datas(){

	GLOBAL  $_SESSION,
			$conn_pdo, $conn,
			$_post_datas,
			$log_param_1,
			$logfile,
			$now,
			$error_level;

			$exit = 0;

	$trials = $_post_datas['trial'] + $_post_datas['level'] * 5 + 20;

	if( isset($_SESSION['user_datas']['name']) || $_SESSION['user_datas']['name'] != ""){

		if($error_level >2) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][UPDATE NBACK DATAS] POST: '.print_r($_post_datas)."\n", $log_param_1);

			$sql="
			UPDATE nbackDatas
			SET
				gameMode='".$_post_datas['gameMode']."',
				level='".$_post_datas['level']."',
				seconds='".$_post_datas['seconds']."',
				trials='".$trials."',
				eventLength='".$_post_datas['eventLength']."',
				color ='".$_post_datas['color']."'
			WHERE
				userID='".$_SESSION['user_datas']['id']."'; ";

		if(Sql_execute_query($sql) === 0){

			if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][UPDATE NBACK DATAS] UPDATE DATAS OK\n", $log_param_1);
			if($error_level >2) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][UPDATE NBACK DATAS] SQL: '.$sql."\n", $log_param_1);

			if($_post_datas['gameMode'] != $_SESSION['nbackDatas']['gameMode']){

				if( Sql_execute_query("
				UPDATE nbackSessions
				SET
					result = 0
				WHERE
					userID= ".$_SESSION['user_datas']['id'].";") == 0){

					if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][UPDATE NBACK DATAS][SET RESULT TO DEFAULT] OK \n", $log_param_1);
				}
				else{
					if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][UPDATE NBACK DATAS][SET RESULT TO DEFAULT] ERROR \n", $log_param_1);
					$exit = "Update_error";
				}

			}

			if (($exit = Load_datas()) != 0) {

				$exit = 'load_error';
			}

		}
		else 	{
			if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][UPDATE NBACK DATAS] ERROR CODE: '.$exit."\n", $log_param_1);
			$exit='update_error';
		}

	}
	else {

			setCookie("gameMode", $_post_datas["gameMode"], time() * 60 * 60 *24 * 365, APPROOT);
			setCookie('level', $_post_datas['level'], time() * 60 * 60 *24 * 365, APPROOT);
			setCookie('seconds', $_post_datas['seconds'], time() * 60 * 60 *24 * 365, APPROOT);
			setCookie('trial', $trials, time() * 60 * 60 *24 * 365, APPROOT);
			setCookie('eventLength', $_post_datas['eventLength'], time() * 60 * 60 *24 * 365, APPROOT);
			setCookie('color', $_post_datas['color'], time() * 60 * 60 *24 * 365, APPROOT);
			setCookie('lastModified',date("Y.m.d H:i:s "), time() * 60 * 60 *24 * 365, APPROOT);

// echo "post-Position: ", $_post_datas['gameMode'],"\n";
// echo "cookie-Position: ", $_COOKIE["gameMode"],"\n";

			if($error_level >0 && $_COOKIE["gameMode"] == $_post_datas['gameMode'])
				file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][UPDATE NBACK DATAS] UPDATE COOKIE: OK\n", $log_param_1);
			else {
				file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][UPDATE NBACK DATAS] UPDATE COOKIE: FALSE\n", $log_param_1);
				$exit = "Update_error";
			}

		}

	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Update_nback_datas] N-Back Option UpLoad exitcode: ', $exit, ' on Update_nback_datas: '.Date('Y-m-d H:i:s')."\n", $log_param_1);

	return $exit;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 	Felhasználói profil létrehozása után amennyiben van feltöltendő file, létrehoz  az edott					  //
//# user nevén mappát, amibe áthelyezi a tmp_dir-ből a feltöltött képet. 										  //
//	Ez az thesis/users/"userID" mappába kerül. Ha nincs a struktúrában USERS mappa, automatikusa készít egyet.	  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Create_user($_post_datas, $file){

	GLOBAL 	$log_param_1,
			$logfile,
			$now,
			$error_level;

	$gen_id = Id_gen(false);

	if( $file!= '' && $file != 'none' )
	{
		$fileName = $gen_id.'_'. $file['file']['name'];
	}
	else $fileName = 'none';


	$users_count = Sql_query('SELECT COUNT(*) as num FROM users;');

// 	A default user utáni második felhasználó root jogosultságot kap.

	if( $gen_id == 2 && $users_count[0]['num'] == 1 || $_post_datas["create_user_user"] == 'admin')
	{
		$privilege = 3;
	}
	else
	{
		$privilege = 1;
	}

	$pass = "salt".md5($_post_datas['create_user_pass']);

	if(isset($_post_datas['create_user_birth']) && $_post_datas['create_user_birth'] != '')
	{
		$sql="INSERT INTO users (name, email, userName, password, birth, passwordLength, fileName, `privilege`) values
			('".Escape_special_characters($_post_datas["create_user_name"])."',
			'".Escape_special_characters($_post_datas['create_user_email'])."',
			'".Escape_special_characters($_post_datas['create_user_user'])."',
			'".md5($pass)."',
			'".$_post_datas['create_user_birth']."',
			'".strlen($_post_datas['create_user_pass'])."',
			'".Escape_special_characters($fileName)."',
			'".$privilege."');";
	}

	else
	{
		$sql="INSERT INTO users (name, email, userName, password, passwordLength, fileName, `privilege`) values
			('".Escape_special_characters($_post_datas["create_user_name"])."',
			'".Escape_special_characters($_post_datas['create_user_email'])."',
			'".Escape_special_characters($_post_datas['create_user_user'])."',
			'".md5($pass)."',
			'".strlen($_post_datas['create_user_pass'])."',
			'".Escape_special_characters($fileName)."',
			'".$privilege."');";
	}


	LogLn(0, "[Create_user] sql script: $sql");
	 
	if(Sql_execute_query($sql) === 0)
	{
		LogLn(1, "[Create_user] Exit Code: 0");
		return 0;
 	}
	else
	{
		LogLn(0, "[Create_user] Exit Code: 1");
 		return 1;
 	}

 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Képfeltöltést végző eljárás. Csak a users/"user" mappába tölt...											  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Load_file_to_server($id, $file )
{
	GLOBAL 	$log_param_1,
			$logfile,
			$now,
			$error_level;

 	if(isset($file['file']['name']) && $file['file']['name'] != 'none')
 	{
 		$file_id_and_name = $id . '_'. $file['file']['name'];
		$path =  'users/'.$id.'/' ;

		 if(!is_dir($path)) 
		 {
 			$oldmask = umask(0);
			mkdir($path, 0777 /*00777 teljes jog*/, true);
			umask($oldmask);
 		}

		if (file_exists($path .  $file_id_and_name)) 
		{
			LogLn(0, "[Load_file_to_server] ".$file_id_and_name." already exists");
		}

		else
		{
			$users_profile_images_name = $path . $file_id_and_name;
			move_uploaded_file($file["file"]["tmp_name"], $users_profile_images_name);
			LogLn(0, "[Load_file_to_server] Stored in: ".$id.'/'.$file_id_and_name);
		}

		$sql = 'UPDATE users SET fileName = "' . $file_id_and_name . '" where id = '. $id .'';
		Sql_execute_query($sql);

		if (!file_exists('users/forum_images/' .$file_id_and_name)) 
		{
			Compress_image($path . $file_id_and_name, 'users/forum_images/' .$file_id_and_name);
		}
 	}
	return 0;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Kép kompresszáló függvény											  										  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Compress_image($source_url, $destination_url)
{
	GLOBAL 	$log_param_1,
			$logfile,
			$now,
			$error_level;

	if(filesize($source_url) > 10000)
	{
		$quality = 2000 / filesize($source_url) *100 ;
	}
	else
	{
		$quality = 100;
	}


	LogLn(0, "[Compress_image] Quality:$quality");
	if(!is_dir('users/forum_images'))
	{
		mkdir('users/forum_images', 0777);
	}

	$info = getimagesize($source_url);

	if($info['mime'] == 'image/jpeg')	$image = imagecreatefromjpeg($source_url);

	elseif ($info['mime'] == 'image/gif')	$image = imagecreatefromgif($source_url);

	elseif ($info['mime'] == 'image/png')	$image = imagecreatefrompng($source_url);

	if(imagejpeg($image, $destination_url, round($quality)) == false)  return 1;

	return 0;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Statisztika számláló fuggvény																			      //
//  a felhasználó utolsó 20 napjának adatait kapja.
// wrong, correct hit, gameMode, timestamp as date
// a $sessions tárolja, hogy hány nap volt a leghosszabb egybefüggő, megszakadsnál nullázódik éskezdi előről.
// összeadja az összes százalékos teljesítményt, majd kiszámolja az átlagot.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function User_tatistic($ut)
{
	GLOBAL 	$log_param_1,
			$logfile,
			$error_level;

	$t = array ('longest_session' => 0, 'avg_percent' => 0 , 'sessions' => array());
	$sessions = 0;
	$sum_percents = 0;
	if(count($ut) > 0)
	{
		for($i=0;$i<count($ut);$i++)
		{
			if($ut[$i]['gameMode'] == 0)
			{
				if($i>0)
				{
					if( strtotime($ut[$i]['date']) - strtotime($ut[$i - 1]['date']) > 24 * 3600 )
					{
						if($sessions > $t['longest_session'])
						{
							$t['longest_session'] = $sessions;
							array_push($t['sessions'], $sessions);
							$sessions = 0;
						}
					}
					elseif(strtotime($ut[$i]['date']) != strtotime($ut[$i - 1]['date']))
					{
						$sessions++;
					}
				}
			$sum_percents += ($ut[$i]['correctHit'] + $ut[$i]['wrongHit']) != 0 ? $ut[$i]['correctHit'] / ($ut[$i]['correctHit'] + $ut[$i]['wrongHit']) * 100 : 0;
			}
		}

		if($sessions > $t['longest_session'])
		{
			$t['longest_session'] = $sessions;
			array_push($t['sessions'], $sessions);
		}
		$t['avg_percent'] = round($sum_percents / count($ut));
	}
	else
	{
		$t['longest_session'] = 0;
		$t['avg_percent'] = 0;
	}
	return $t;
 }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# Ez a függvény előre lekéri a következő AUTO_INCREMENT értékét és a paraméteről függően (true, false)	      //
//  levon belőle 1-et, majdkiegészíszi vezető nullákkal.														  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Id_gen($was_upload)
{
		GLOBAL 	$database,
				$log_param_1,
				$logfile,
				$error_level;

		$sql='SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.$database.'" and table_name = "users";';
		$new_id  = json_decode(
						json_encode(
							Sql_query($sql)
									), TRUE
								);

		$id = $new_id[0]['AUTO_INCREMENT'];
		settype($id, "integer");

		if($was_upload)
		{
			$id -=1;
		}

		$diff = 8 - strlen($new_id[0]['AUTO_INCREMENT']);

		while($diff-- != 0)
		{
			$id = '0'.$id;
		}
		return $id;
}


function Accociative_array_to_string_recurse($exit, $t, $i)
{
	if(count($t) != 0)
	{
		$exit = "\n";
		foreach($t as $key=>$value)
		{
			if(is_array($value) && count($value) != 0)
			{
				$exit .= "\n[".$key."]";
				$exit .= Accociative_array_to_string_recurse($exit, $value, ++$i);
				$i--;
			}
			else
			{
				for($j=0;$j<$i;$j++) $exit.="  ";
				$exit .='"'.$key.'" : '.$value.",\n";
			}
		}
	}
	return $exit;


}

//////////////////////////////////////////////////////////////////////////////////////////
// Log required: min error level, message												//
//////////////////////////////////////////////////////////////////////////////////////////
function LogLn($level, $message)
{
	global	$error_level,
			$log_param_1,
			$logfile,
			$now;

	if($error_level > $level) 
		file_put_contents(APPLICATION.$logfile, "[".date("Y-m-d H:i:s")."][{$_SERVER['REMOTE_ADDR']}]".$message.PHP_EOL, $log_param_1);
}

