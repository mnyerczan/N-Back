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
			$user_id,
			$img_name,
			$log_param_1,
			$logfile,
			$now,
			$error_level;

	$exit = 1;
	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Delete_user] ***SART***\n", $log_param_1);

	if($error_level > 1)file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][Delete_user] user ID: '.$user_id."\n", $log_param_1);

	if($user_id != 'none' ){
		$remove_user_sql = '
			DELETE FROM users
			WHERE
				id = "'.$user_id.'";';
		if($img_name != 'none'){
			Delete_image_from_server();
		}

		$exit = Sql_execute_query($remove_user_sql);

		if($user_id == $_SESSION['user_datas']['id']){
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
			$user_id,
			$img_name,
			$log_param_1,
			$logfile,
			$now,
			$error_level;
	$exit = 1;

	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] *** START ***\n", $log_param_1);


	if($user_id != '' && $user_id != 'none'){
		$img_name = Sql_query('
			SELECT
				file_name
			FROM users
			WHERE
				id = '.$user_id.';')[0]['file_name'];


		$sql_remove_image = '
			UPDATE users
			SET
				file_name = "none"
			where
				id = '.$user_id.';';

		if(Sql_execute_query($sql_remove_image) == 0 ){

			if(file_exists('users/'.$user_id. '/' . $img_name))	$exit = unlink('users/'.$user_id. '/' . $img_name);
			elseif($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: Profile image not exists\n", $log_param_1);

			if(file_exists('users/forum_images/' . $img_name)) $exit = unlink('users/forum_images/' . $img_name);
			elseif($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: Forum image not exists\n", $log_param_1);

			if(is_dir('users'.'/'.$user_id)) rmdir('users'.'/'.$user_id);
			elseif($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]DELETE_IMAGE_FROM_SERVER] ERROR: users/".$user_id." Directory not exists\n", $log_param_1);

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
			$user_id,
			$now;
			$exit = 1;

	$pass = "none";
	$_post_pass = "none";

	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] ***START***'.\n", $log_param_1);


	#Adatmódosításnál
	if(isset($_post_datas['u_name']) || isset($_post_datas['u_name_original'])){

		if($_post_datas['u_name'] != NULL || $_post_datas['u_name'] != '') $login_name = $_post_datas['u_name'];
		else $login_name = $_post_datas['u_name_original'];

		if( isset($_post_datas['pass']) && strlen($_post_datas['pass']) < 4) $_post_pass = $pass = $_post_datas['pass_original'];
		else $pass = md5("salt".md5($_post_pass = $_post_datas['pass']));

		if($error_level >1) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] POST[pass]: ".(isset($_post_datas['pass'])?$_post_datas['pass']:'none')."\n".
														"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] POST[pass_original]: "."\n".
														"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][LOAD DATAS] FINAL PASS: ".$_post_pass."\n",$log_param_1);

		$sql_nb="
				select
					n.id,
					n.manual,
					n.level,
					n.seconds,
					n.trials,
					n.event_length,
					n.color,
					current_timestamp AS refresh
				from users u, n_back_datas n
				where u.id = n.user_id
					and u.u_name = '".Escape_special_characters($login_name)."'
					and u.password='".$pass."';";

		$sql_user="
				select * from users
				where
					u_name='".Escape_special_characters($login_name)."'
					and password='".$pass."';";
	}
	#Egyébb: pl. témamódosítás után.
	elseif( $user_id != 'none')
	{
		#módosítás: az értékadás után módosítja a $_SESSION tartalmát, így a lekérdezés eredménye zero.
		$_SESSION = $_SESSION;

		$sql_nb="select
					n.id,
					n.manual,
					n.level,
					n.seconds,
					n.trials,
					n.event_length,
					n.color,
					current_timestamp AS refresh
				from users u, n_back_datas n
				where u.id = n.user_id
					and u.id = '".$user_id."'
					and u.password='".$_SESSION['user_datas']['password']."';";

		$sql_user="select * from users
				where u_name='".$_SESSION['user_datas']['u_name']."'
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
		$_SESSION['user_datas']['login_datetime']	= $result_user[0]['login_datetime'];
		$_SESSION['user_datas']['u_name']			= Include_special_characters($result_user[0]['u_name']);
		$_SESSION['user_datas']['privilege']		= $result_user[0]['privilege'];
		$_SESSION['user_datas']['password']			= $result_user[0]['password'];
		$_SESSION['user_datas']['birth']				= $result_user[0]['birth'];
		$_SESSION['user_datas']['pw_length']		= $result_user[0]['pw_length'];
		$_SESSION['user_datas']['file_name']		= $result_user[0]['file_name'];
		$_SESSION['user_datas']['theme']				= $result_user[0]['theme'];
		$_SESSION['user_datas']['online'] 			= $result_user[0]['online'];
		$_SESSION['user_datas']['refresh'] 			= $result_nb[0]['refresh'];


		$_SESSION['n_back_datas']['id'] 				= $result_nb[0]['id'];
		$_SESSION['n_back_datas']['manual'] 		= $result_nb[0]['manual'];
		$_SESSION['n_back_datas']['level'] 			= $result_nb[0]['level'];
		$_SESSION['n_back_datas']['seconds']		= $result_nb[0]['seconds'];
		$_SESSION['n_back_datas']['trials'] 		= $result_nb[0]['trials'];
		$_SESSION['n_back_datas']['event_length'] = $result_nb[0]['event_length'];
		$_SESSION['n_back_datas']['color'] 			= $result_nb[0]['color'];

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

	$manual = (isset($_cookie_datas['manual']) && $_cookie_datas['manual'] == 'Off') ? 0 : 1;

	if(isset($_SESSION['user_datas']['id']))
	{

			$sql_insert_session = 'INSERT INTO n_back_sessions (user_id,ip,level,correct_hit,wrong_hit,time_length,manual, result) VALUES
				("'.$_SESSION['user_datas']['id'].'",
				"'.$_SERVER['REMOTE_ADDR'].'",
				"'.$_SESSION['n_back_datas']['level'].'",
				"'.$_cookie_datas['correct_hit'].'",
				"'.$_cookie_datas['wrong_hit'].'",
				"'.$_cookie_datas['time_length'].'",
				"'.$manual.'",
				"'.$result.'");';

	}
	else{
			$sql_insert_session='INSERT INTO n_back_sessions (user_id,ip,level,correct_hit,wrong_hit,time_length,manual, result) VALUES
					("1",
					"'.$_SERVER['REMOTE_ADDR'].'",
					"'.$_cookie_datas['level'].'",
					"'.$_cookie_datas['correct_hit'].'",
					"'.$_cookie_datas['wrong_hit'].'",
					"'.$_cookie_datas['time_length'].'",
					"'.$manual.'",
					"'.$result.'");';

		}
	
	if(isset($_cookie_datas['n_back_is_upload']) && $_cookie_datas['n_back_is_upload'] == '0' && Sql_execute_query($sql_insert_session) === 0)
	{
		if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Insert_nb_sessions] SQL : ", $sql_insert_session."\n", $log_param_1);
		setCookie('n_back_is_upload','1');
		setCookie('last_modified',date("Y.m.d H:i:s "));
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

		if($_cookie_datas['manual'] == 'Off'){

			if($_cookie_datas['correct_hit'] > 0 ){

				$pro_res = $_cookie_datas['correct_hit'] / ($_cookie_datas['correct_hit'] + $_cookie_datas['wrong_hit']) * 100;
				$exit = ($pro_res >= 80 ) ? '1' : (($pro_res > 50 || $_cookie_datas['level'] == 1) ? '0' : '-1');

				if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][CREATE SESSION RESUL]][POSITION] RESULT: '.$exit."\n", $log_param_1);
				return $exit;
			}
			if($_cookie_datas['wrong_hit'] > 0 &&  $_cookie_datas['level'] > 1){

				if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][CREATE SESSION RESUL][POSITION] RESuLT: -1\n", $log_param_1);
				return -1;
			}
		}
		if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][CREATE SESSION RESUL][MANUAL] RESULT: 0\n", $log_param_1);
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
			$user_id,
			$log_param_1,
			$logfile,
			$now,
			$error_level;

	$exit = 1;

	if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas] ***START***\n", $log_param_1);
	if($error_level > 2) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][Modify_user_datas][POST]".print_r($_POST, true)."\n", $log_param_1);

	if($user_id != 'none'){

		$exit = Load_file_to_server($user_id, $file);
		if($_post_datas['mail'] == null || $_post_datas['mail'] == '')	$mail= $_post_datas['mail_original'];
		else $mail= $_post_datas['mail'];

		if( $_post_datas['u_name'] == null || $_post_datas['u_name'] == '') $u_name = $_post_datas['u_name_original'];
		else $u_name = $_post_datas['u_name'];

		if($_post_datas['pass'] != null && $_post_datas['pass'] != '' && strlen($_post_datas['pass']) > 3){
			$pass = md5("salt".md5( $_post_datas['pass']));
			$_post_pass = strlen($_post_datas['pass']);
		}
		else {
			$pass = $_post_datas['pass_original'];
			$_post_pass = $_post_datas['pw_length_original'];
		}

		$privilege = isset($_POST['privilege']) ? $_POST['privilege'] : $_SESSION['user_datas']['privilege'];
		$sql = "
		UPDATE users
		SET
			u_name='".Escape_special_characters($u_name)."',
			email='".Escape_special_characters($mail)."',
			password='".$pass."' ,
			pw_length = '".$_post_pass."' ,
			privilege='".$privilege."'
		where
			id='".$user_id."';";

		if (Sql_execute_query($sql) === 0){

			if($user_id == $_SESSION['user_datas']['id']){
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
			UPDATE n_back_datas
			SET
				manual='".$_post_datas['manual']."',
				level='".$_post_datas['level']."',
				seconds='".$_post_datas['seconds']."',
				trials='".$trials."',
				event_length='".$_post_datas['event_length']."',
				color ='".$_post_datas['color']."'
			WHERE
				user_id='".$_SESSION['user_datas']['id']."'; ";

		if(Sql_execute_query($sql) === 0){

			if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][UPDATE NBACK DATAS] UPDATE DATAS OK\n", $log_param_1);
			if($error_level >2) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][UPDATE NBACK DATAS] SQL: '.$sql."\n", $log_param_1);

			if($_post_datas['manual'] != $_SESSION['n_back_datas']['manual']){

				if( Sql_execute_query("
				UPDATE n_back_sessions
				SET
					result = 0
				WHERE
					user_id= ".$_SESSION['user_datas']['id'].";") == 0){

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

			setCookie("manual", $_post_datas["manual"]);
			setCookie('level', $_post_datas['level']);
			setCookie('seconds', $_post_datas['seconds']);
			setCookie('trial', $trials);
			setCookie('event_length', $_post_datas['event_length']);
			setCookie('color', $_post_datas['color']);
			setCookie('last_modified',date("Y.m.d H:i:s "));

// echo "post-Manual: ", $_post_datas['manual'],"\n";
// echo "cookie-Manual: ", $_COOKIE["manual"],"\n";

			if($error_level >0 && $_COOKIE["manual"] == $_post_datas['manual'])
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
//	Ez az thesis/users/"user_id" mappába kerül. Ha nincs a struktúrában USERS mappa, automatikusa készít egyet.	  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Create_user($_post_datas, $file){

	GLOBAL 	$log_param_1,
			$logfile,
			$now,
			$error_level;

	$gen_id = Id_gen(false);

	if( $file!= '' && $file != 'none' )
	{
		$file_name = $gen_id.'_'. $file['file']['name'];
	}
	else $file_name = 'none';


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
		$sql="INSERT INTO users (name, email, u_name, password, birth, pw_length, file_name, `privilege`) values
			('".Escape_special_characters($_post_datas["create_user_name"])."',
			'".Escape_special_characters($_post_datas['create_user_email'])."',
			'".Escape_special_characters($_post_datas['create_user_user'])."',
			'".md5($pass)."',
			'".$_post_datas['create_user_birth']."',
			'".strlen($_post_datas['create_user_pass'])."',
			'".Escape_special_characters($file_name)."',
			'".$privilege."');";
	}

	else
	{
		$sql="INSERT INTO users (name, email, u_name, password, pw_length, file_name, `privilege`) values
			('".Escape_special_characters($_post_datas["create_user_name"])."',
			'".Escape_special_characters($_post_datas['create_user_email'])."',
			'".Escape_special_characters($_post_datas['create_user_user'])."',
			'".md5($pass)."',
			'".strlen($_post_datas['create_user_pass'])."',
			'".Escape_special_characters($file_name)."',
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

		$sql = 'UPDATE users SET file_name = "' . $file_id_and_name . '" where id = '. $id .'';
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
// wrong, correct hit, manual, timestamp as date
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
			if($ut[$i]['manual'] == 0)
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
			$sum_percents += ($ut[$i]['correct_hit'] + $ut[$i]['wrong_hit']) != 0 ? $ut[$i]['correct_hit'] / ($ut[$i]['correct_hit'] + $ut[$i]['wrong_hit']) * 100 : 0;
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

	if($error_level >$level) 
		file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."]".$message.PHP_EOL, $log_param_1);
}

?>