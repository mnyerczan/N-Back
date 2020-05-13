<!-- Prifiles -->
<?php

$case =isset( $_GET['choose'] ) ? $_GET['choose']: 0;
$offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][PROFILE] ***SART***\n", $log_param_1);
if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][PROFILE][GETNUMOFUSERS]START\n", $log_param_1);

$count = Sql_query('
	SELECT count(*) as count FROM users where id != 1;')[0]['count'];
if($error_level > 0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][PROFILE][GETNUMOFUSERS] END\n", $log_param_1);

if(isset($_COOKIE['plimit']))
	$limit = $_COOKIE['plimit'];
else {
	$limit = 6;
	setcookie("plimit", 6 , time() * 60 * 60 *24 * 365, APPROOT);
}
$profiles_count =  ($count  / $limit) > 0 ? $count : 1;


?>

<script type="text/javascript">
function Profile_form_radio_checked(type){

	//profiles.php radio button enevt handler
	var current_order = '<?php echo $_GET['choose']; ?>';
	console.log("#");

	if(type != current_order){
		switch(type){
  			case '0': window.location= "index.php?index=8&choose=0"; break;
  			case '1': window.location= "index.php?index=8&choose=1"; break;
  			case '2': window.location= "index.php?index=8&choose=2"; break;
		}
	}
 }

function Send_id(e){
	document.getElementById("uid_input").value = e;
	document.getElementById("Send_id_post").submit();
}


</script >

<form action="index.php?index=6" method="POST" id="Send_id_post" name="Send_id_post" hidden>
	<input type="text" name="id" id="uid_input" hidden readonly/>
	<input type="text" name="offset" id="offset" value="" hidden readonly/>
</form>

<div id="profiles_table">
<div id="profils_select_container">
	<div id="search_field_container">
		<input name="profils_select_input" id="profils_select_input" placeholder="Search" value="" type="text" onkeypress="was_enter_down(event)">
		<button id="profils_select_submit" type="submit" name="profils_select_submit" value="Go" onclick="Profile_form_search_by_string()"></button>
	</div>

	<div id="num_of_items_of_profiles_container" style="float: left;">
		Num of profiles:
		<input type="text" name="num_of_items" id="num_of_items" placeholder="<?php echo $limit; ?>" style="width: 30px;"
			onkeydown="Was_enter_down_p(event)" />
		<div id="alert_div_p"></div>
	</div>

	<div id="prifiles_order_by_radio_container">
		<label class="radio_container">
			<input type="radio" class="create_forum_item_radio" name="order_by" value="0" onclick="Profile_form_radio_checked('0')" <?php echo $case == '0' ? 'checked' : ''; ?> >Performance
			<span class="circle" ><span>
		</label>
		<label class="radio_container">
			<input type="radio" class="create_forum_item_radio" name="order_by" value="1" onclick="Profile_form_radio_checked('1')" <?php echo $case == '1' ? 'checked' : ''; ?>>A - Z
			<span class="circle"><span>
		</label>
		<label class="radio_container">
			<input type="radio" class="create_forum_item_radio" name="order_by" value="2" onclick="Profile_form_radio_checked('2')" <?php echo $case == '2' ? 'checked' : ''; ?>>Z - A
			<span class="circle"><span>
		</label>
	</div>
<?php
if($count / $limit > 1)
echo
	'<div id="navigator_container">
		<div id="navigator_inside_container">
			<div >
				<img src="img/left_arrow_blue.png" class="n_back_info_navigation_img" onmousedown="profiles_page_back();"/>
				<img src="img/right_arrow_blue.png" class="n_back_info_navigation_img" style="float: right;" onmousedown="profiles_page_forward();"/>
			</div >
		</div>
	</div>';
?>
</div>
<?php
if(isset($_SESSION['user_datas']['id'])){
	switch($case){

		case '0': {
				#rendezés teljesítmény szerint

				Calculate_users_datas(Sql_query($sql_profile = 'SELECT u.name AS nev, u.id as uid, substr(u.loginDatetime, 1, 10) AS registry, n.level AS szint, u.fileName AS profil, online
				FROM users AS u, nbackDatas as n where u.privilege != "0" and u.id = n.userID LIMIT  '.$limit.' OFFSET  '.( $offset * $limit ).';'));
		}break;
		case '1':{
				Calculate_users_datas(Sql_query($sql_profile = 'SELECT u.name AS nev, u.id as uid, substr(u.loginDatetime, 1, 10) AS registry, n.level AS szint, u.fileName AS profil, online
				FROM users AS u, nbackDatas as n where u.privilege != "0" and u.id = n.userID LIMIT  '.$limit.' OFFSET  '.( $offset * $limit ).';'));

		} break;
		case '2':{
				Calculate_users_datas(Sql_query($sql_profile = 'SELECT u.name AS nev, u.id as uid, substr(u.loginDatetime, 1, 10) AS registry, n.level AS szint, u.fileName AS profil, online
				FROM users AS u, nbackDatas as n where u.privilege != "0" and u.id = n.userID ORDER BY name DESC LIMIT  '.$limit.' OFFSET  '.( $offset * $limit ).' ;'));
		}break;
		case '3':{
				$search_string = $_GET['s'];
				Calculate_users_datas(Sql_query($sql_profile = 'SELECT u.name AS nev, u.id as uid, substr(u.loginDatetime, 1, 10) AS registry, n.level AS szint, u.fileName AS profil, online
				FROM users AS u, nbackDatas as n WHERE u.privilege != "0" AND u.id = n.userID AND (u.name LIKE "%'.$search_string.'%" OR  substr(u.loginDatetime, 1, 10) LIKE "%'.$search_string.'%"
				OR n.level LIKE "%'.$search_string.'%" ) LIMIT  '.$limit.' OFFSET  '.( $offset * $limit ).' ;'));
		}break;
	}
}
else header('Location: index.php');
?>
<script>
<?php echo
'var pages = '.ceil($profiles_count / $limit).',
	current_page = '.(isset($offset) ? $offset : 0).',
	choose = '.$_GET['choose'].';';
?>
function  profiles_page_back(){
		if(current_page > 0)
		{
			current_page--;
			document.getElementById("Send_id_post").action="index.php?index=8&choose="+choose;
			document.getElementById("offset").value = current_page;
			document.getElementById("Send_id_post").submit();
		}
	}
function  profiles_page_forward(){
		if(current_page < pages -1)
		{
			current_page++;
			document.getElementById("Send_id_post").action="index.php?index=8&choose="+choose;
			document.getElementById("offset").value = current_page;
			document.getElementById("Send_id_post").submit();
		}
	}
</script>
</div>

<!-- Profiles end -->

<?php

# Functions


function Write_out_profile($users_query, $i){

	GLOBAL	$main_theme,
			$offset,
			$_SESSION,
			$limit;

echo 	'<div class="profile_container">
			<div class="profile_datas_container">
				<div style="float: left; font-family: Comic Sans MS;'.($_SESSION['user_datas']['name'] == $users_query[$i]['nev'] ? "color:#558;" : "").'">'.Include_special_characters($users_query[$i]['nev']).'</div>';

if($users_query[$i]["online"] == "1"){
	echo "<b class='online_ring'>&#9679</b>";
}

if($_GET['choose'] == '0'){
	echo	   '<div class="rated_container"><span>',($offset * $limit + $i + 1),'</span></div>';
}

echo		   '<br>Registation date: '.$users_query[$i]['registry'].'
				<br>Level: '.$users_query[$i]['szint'].'
				<br>Performance: ', $users_query[$i]['avg_percent'] ,' %
				<br>Points: ', $users_query[$i]['points'];
			$link = explode('_', $users_query[$i]['profil']);
echo
			'</div>
			<div class="profile_image_container"><img src="',$link[0] != 'none' ? 'users/'.$link[0].'/'.$users_query[$i]['profil'] : 'img/default_user_black.png', '"></div>
		</div>';
 }



function Calculate_users_datas($users_query){

	GLOBAL 	$_SESSION,
			$case;


	if(count($users_query) != 0){

		for($i = 0; $i < count($users_query); $i++){

			$sql_statistic = 'select substr(timestamp, 1, 10) as date, sum(correctHit) as correctHit, sum(wrongHit) as wrongHit, max(gameMode) as gameMode from nbackSessions
			where userID="'.$users_query[$i]['uid'].'" group by date ORDER BY date DESC LIMIt 30;';

			$t = Sql_query($sql_statistic);
			$stat = User_tatistic($t);
			$points = 0;
			$p = 0;

			foreach($stat['sessions'] as $key => $value){

				if($value < 5) 		$p += $value * 1;
				elseif($value < 10) $p += $value * 2;
				elseif($value < 15) $p += $value * 4;
				elseif($value < 20) $p += $value * 8;
				elseif($value < 30) $p += $value * 16;
			}

			$points = round($p * $stat['avg_percent'], 2);
			$users_query[$i]['points'] = $points;
			$users_query[$i]['avg_percent'] = $stat['avg_percent'];
		}
		if($case == '0')
			$users_query = Sort_users_array($users_query);

		for($i = 0; $i < count($users_query); $i++){

			if($_SESSION['user_datas']['privilege'] == 3){
				?>
				<a  style="outline: none; cursor: pointer;" onclick="Send_id('<?php echo $users_query[$i]['uid']; ?>')" >
				<?php
					Write_out_profile($users_query, $i);
				echo '</a>';
			}
			else{
					Write_out_profile($users_query, $i);
			}
		}
	}
	else{
		echo '<h2 style="text-align: center;">Set is empty</h2>';
	}
 }




function Sort_users_array($users_query){

	for($i=count($users_query); $i > 0; $i--){

		for($j=1;$j<$i; $j++){

			if($users_query[$j - 1]['points'] < $users_query[$j]['points']){

				$q = $users_query[$j];
				$users_query[$j] = $users_query[$j - 1];
				$users_query[$j - 1] = $q;

			}
		}
	}

	return $users_query;
 }
?>
