<?php
require_once('.././special_characters_handler.php');
require_once('.././connect.php');
require_once('.././functions.php');
require_once('.././globals.php');

mysqli_set_charset($conn, "utf8");

if(isset($_GET['count']) && isset($_GET['rid'])){

	$last_count = $_GET['count'];
	$sql_check = 'SELECT count(*) t FROM logs l, users u where u.id = l.userID and  menuID="'.$_GET['rid'].'";';
	$current_count = Sql_query($sql_check)['0']['t'];

	echo $current_count;

}

elseif(isset($_GET['get_logs'])){

	$uid		= $_GET['uid'];
	$menuID	= $_GET['rid'];
	$limit  	= $_GET['limit'];
	$offset 	= $_GET['offset'] * $limit;
	$privilege 	= $_GET['privilege'];
	$user_priv	= $_GET['user_priv'];
	$main_theme = $_GET['main_theme'];


	$sql_query =  'SELECT l.title,l.id as logid, u.id AS userID,u.name AS username,l.content AS msg, l.timestamp, online, u.fileName FROM users u, logs l WHERE u.id = l.userID and l.menuID = '.$menuID.' ORDER BY l.id desc LIMIT '.$limit.' OFFSET '.$offset.';';
	$get_logs = $_GET['get_logs'];

	$result1=mysqli_query($conn, $sql_query);


	if(count($get_menus_name = sql_query("SELECT name, privilege FROM menus WHERE id = ".$menuID." and parentID = '00000001';")) > 0 ){
		if($get_menus_name[0]['privilege'] <= $user_priv){
			echo '<h1 style="text-align: center;">'.Include_special_characters($get_menus_name[0]['name']).'</h1>';

			while( $extract = mysqli_fetch_array($result1))
			{
				$msg = Include_special_characters($extract['msg']);
				$title = Include_special_characters($extract['title']);

				echo '
				<div class="log_container" style=" background-color: '.($extract['logid'] % 2 == 0 ? ' white' : '#e5e5e5' ).'; height: '.round((strlen($msg) / 92) * 15 +15).'px !important;">

					<div class="right_side_container" style="background-color:transparent;">

						<div class="log_header" style="background-color: '.($extract['logid'] % 2 == 0 ? ' #f1f1f1;' : 'white' ).';">

							<div class="log_header_left_container">
								Posted by <span style="font-size:11px; font-family: Comic Sans MS; '.($extract['userID'] == $uid ? 'color: #557;' : '').'">'.$extract['username'].'</span>'.
								($extract["online"] == 1 ? "<b class='online_ring'>&#9679</b>" : "")
								.'<div style="float: right; width: 30px; display: table; border-left: 1px solid #666; padding-left: 10px;">#'.round($extract['logid']).'</div>
								<div style="float: right; width: 200px; display: table; text-align: center;">'.$extract['timestamp'].'</div>
							</div>
							<div>
								<div class="log_header_title"><img src="img/comment.png" style="width: 10px;"> '.$title.'</div>
								<div>',
								($privilege == 3 || $uid == $extract['userID']) ? '<a href="#" id="delete_msg_link" onclick="Delete_log('.round($extract['logid']).');" style="float: right;color:black;">Delete message</a>' : ''
								,'</div>
							</div>
						</div>
						<div class="log_msg_container" >'.$msg.'</div>
					</div>

					<div class="image_container">'
					.(($extract['fileName'] != 'none') ?'
					<img src="users/forum_images/'. $extract['fileName'].'">
					':'<img src="'.($main_theme == "white" ? 'img/default_user_black.png' : 'img/default_user_white.png').'" style=" float: right; background-color: #c3babd;">
					'
					).'
					</div>

				</div>
				<br>';
			}
		}
	}
	else echo '<div id="n_back_modify_level"><span color="#0a0">Empty</span></div>';
}

mysqli_close($conn);
$conn_pdo = null;
 ?>


