 <?php
require_once('.././special_characters_handler.php');
require_once('.././connect.php');
require_once('.././functions.php');
require_once('.././globals.php');
mysqli_set_charset($conn, "utf8");


 $uname		= 	$_POST['uname'];
 $msg  		=  	Escape_special_characters($_POST['msg']);
 $menu_id	= 	$_POST['menu_id'];
 $uid 		=   $_POST['uid'];
 $title 		=	Escape_special_characters($_POST['title']);
 $limit     =   $_POST['limit'];
 $offset    =   $_POST['offset'] * $limit;
 $privilege =	$_POST['privilege'];
 $user_priv	=	$_POST['user_priv'];
 $main_theme= 	$_POST['main_theme'];
 $logfile   =   ".././log/thesis.log";

if($error_level >0) file_put_contents($logfile, "\n[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][FORUM][GETROOMNAMEBYID] *** START ***\n", $log_param_1);
if(count($get_name = sql_query("SELECT name, privilege FROM menus WHERE id = ".$menu_id." and parent_id = '00000001';")) > 0 ){
if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][FORUM][GETROOMNAMEBYID] *** END ***\n", $log_param_1);

$room_name = Include_special_characters($get_name[0]['name']);

	if($get_name[0]['privilege'] <= $user_priv){
		echo '<h1 style="text-align: center;">'.Include_special_characters($get_name[0]['name']).'</h1>';
	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][INSERT TO '".$room_name."'][".$uname."] *** START ***\n", $log_param_1);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////#
#																																																																	#
#   Ha megtöröm a stringeket, whitespace karakterek hibát generálhatnak.																																															#
#																																																																	#
#    Insert SQL script. 																																																											#
#																																																																	#

 		$res = Sql_execute_query($sql_insert =  "INSERT INTO logs(user_id,content,title, menu_id)VALUES ((SELECT id FROM users WHERE name = '".$uname."' LIMIT 1),'".$msg."','".$title."','".$menu_id."');");

#																																																																	#
#	 Query SQL script																																																												#
/*
		$sql_query =  'SELECT l.title,l.id as logid, u.id AS user_id,u.name AS username,l.content AS msg, l.timestamp, u.file_name, u.online FROM users u, logs l WHERE u.id = l.user_id and l.menu_id = '.$menu_id.' ORDER BY l.id desc LIMIT '.$limit.' OFFSET '.$offset.';';

#///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////#

		$result1=mysqli_query($conn, $sql_query);

		while( $extract = mysqli_fetch_array($result1))
		{

			$msg = Include_special_characters($extract['msg']);
			$title = Include_special_characters($extract['title']);

			echo '
				<div class="log_container" style=" background-color: '.($extract['logid'] % 2 == 0 ? ' white' : '#e5e5e5' ).'; height: '.round((strlen($msg) / 92) * 15 +15).'px !important;">

					<div class="right_side_container" style="background-color:transparent;">

						<div class="log_header" style="background-color: '.($extract['logid'] % 2 == 0 ? ' #f1f1f1;' : 'white' ).';">

							<div class="log_header_left_container">
								Posted by <span style="font-size:11px; font-family: Comic Sans MS; '.($extract['user_id'] == $uid ? 'color: #557;' : '').'">'.$extract['username'].'</span>'.
								($extract["online"] == 1 ? "<b class='online_ring'>&#9679</b>" : "")
								.'<div style="float: right; width: 30px; display: table; border-left: 1px solid #666; padding-left: 10px;">#'.round($extract['logid']).'</div>
								<div style="float: right; width: 200px; display: table; text-align: center;">'.$extract['timestamp'].'</div>
							</div>
							<div>
								<div class="log_header_title"><img src="img/comment.png" style="width: 10px;"> '.$title.'</div>
								<div>',
								($privilege == 3 || $uid == $extract['user_id']) ? '<a href="#" id="delete_msg_link" onclick="Delete_log('.round($extract['logid']).');" style="float: right;color:black;">Delete message</a>' : ''
								,'</div>
							</div>
						</div>
						<div class="log_msg_container" >'.$msg.'</div>
					</div>

					<div class="image_container">'
					.(($extract['file_name'] != 'none') ?'
					<img src="users/forum_images/'. $extract['file_name'].'">
					':'<img src="'.($main_theme == "white" ? 'img/default_user_black.png' : 'img/default_user_white.png').'" style=" float: right; background-color: #c3babd;">
					'
					).'
					</div>

				</div>
				<br>';
		}*/

	}
echo '<div id="n_back_modify_level"><img src="img/test_images/loaderColorful.gif" alt="Loading" style="backround-size: 100% 100%;"></div>';

if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][INSERT TO '".$room_name."'][".$uname."] *** END ***\n", $log_param_1);
}
mysqli_close($conn);
$conn_pdo = null;
 ?>


