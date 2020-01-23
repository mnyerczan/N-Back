<div class="nav_bar">
<div class="main_header_style_left">
	<a href="index.php" title="Main page" >
		<img id="main_header_img" style="height: 40px;width: 40px;" src="img/brain_logo.png" >
		<h1 style="float:left; margin-left: 30px" >N-Back</h1>
	</a>
</div>
<div id="nav_bar_link_container">

<div id="false_indicator_container" style = "padding-left: 10px; ">
	<div id="false_indcator_0"></div>
	<div id="false_indcator_1"></div>
	<div id="increase_indcator"></div>
</div>

<script type="text/javascript">
function S_datas(p, q, r, s ){

	document.getElementById("srdp").action = "index.php"+p;
	document.getElementById("rid_input").value = q;
	document.getElementById("r_input").value = r;
	document.getElementById("rp_input").value = s;
   	document.getElementById("srdp").submit();

}
</script>

<form action="" method="POST" id="srdp" name="srdp" hidden>
	<input type="text" name="rp" id="rp_input" hidden readonly/>
	<input type="text" name="rid" id="rid_input" hidden readonly/>
	<input type="text" name="r" id="r_input" hidden readonly/>
</form>


<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////¨//
// 	A navigációs linkek beolvasásáért felelős programrész																			   						   //
//  A jogosultság:																																			   //
//																																							   //
	      $privilege = (isset($_SESSION['user_datas']['privilege']) && $_SESSION['user_datas']['privilege']) ? $_SESSION['user_datas']['privilege'] : '0';     //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////_//

LogLn(0, "[NAVBAR] *** START ***");

LogLn(1, "[NAVBAR][GETPARENTITEMS]***START***");
	$nav_bar_links = Sql_query('SELECT * FROM menus WHERE parent_id = "none" ORDER BY child ASC, name ASC;');
LogLn(1, "[NAVBAR][GETPARENTITEMS] ***END***");

$index = 0;
$was_id = array();
$parent_links_index_array = array();
$child_links_index_array;
$num_of_non_child_item = array();

for($i = 0; $i < sizeof($nav_bar_links); $i++)
{

	$name = strlen(Include_special_characters($nav_bar_links[$i]['name'])) > 8 ? substr(Include_special_characters($nav_bar_links[$i]['name']), 0, 8).'..' : Include_special_characters($nav_bar_links[$i]['name']);

	$child_links_index_array = 0;

	if($nav_bar_links[$i]['parent_id'] == 'none' )
	{
		echo    '<div id="nav_bar_child_links_container_'.$nav_bar_links[$i]['id'].'">';

		if($nav_bar_links[$i]['child'] == '1')
		{

			//////////////////////////////////////////////////////////////////////
			//    Azért csak itt vizsgálom a jogosultásgot, mert elég, ha       //
			//	  csak az almenük nem jelennek meg. Belépni így nem lehet.      //
			// 																	//
			//                   rid = room id                                  //
			//////////////////////////////////////////////////////////////////////

			if($nav_bar_links[$i]['privilege'] > $privilege && $nav_bar_links[$i]['privilege'] != 3)
			{
				////////////////////////////////////////
				#   Szülő, de nincs jogosultság hozzá  #
				////////////////////////////////////////

			echo    '
						<p class="nav_bar_paragraph" id="id_'.$nav_bar_links[$i]['id'].'" style="cursor: pointer;" data-toggle="tooltip" title="Access denied">
								<img src="img/down_white.png" style="width: 8px;" >	'.Include_special_characters($nav_bar_links[$i]['name']).'
								'.($nav_bar_links[$i]['ikon'] != 'none' ? '<img src="'.$nav_bar_links[$i]['ikon'].'" class="header_button_img" style="width: 20px;">' : '').'
						</p>
						';
			}
			elseif($nav_bar_links[$i]['privilege'] <= $privilege || $privilege == 3)
			{
				////////////////////////
				#	Szülő és elérhető  #
				////////////////////////


			echo    '<p class="nav_bar_paragraph" id="id_'.$nav_bar_links[$i]['id'].'" style="cursor: pointer; ">
						<img src="img/down_white.png" style="width: 8px;">
						'.Include_special_characters($nav_bar_links[$i]['name']).'
						'.($nav_bar_links[$i]['ikon'] != 'none' ? '<img src="'.$nav_bar_links[$i]['ikon'].'" class="header_button_img" style="width: 20px;">' : '').'
						</p>
					';

			echo 	'<div class="nav_bar_child_links_content" id="nav_bar_child_links_content_'.$nav_bar_links[$i]['id'].'">';

				LogLn(1, "[NAVBAR][GETNONPARENTITEMSTO: ".$nav_bar_links[$i]['id']."]***START***");

				$nav_bar_child_links = Sql_query($sql = '
	SELECT * FROM menus where parent_id = '.$nav_bar_links[$i]['id'].';');


				LogLn(1, "[NAVBAR][GETNONPARENTITEMSTO: ".$nav_bar_links[$i]['id']."] ***END***");
				for($j = 0; $j < count($nav_bar_child_links); $j++)
				{

					////////////////
					#	Gyerekek   #
					////////////////

					if($nav_bar_child_links[$j]['privilege'] <= $privilege)
					{

						$child_name = strlen(Include_special_characters($nav_bar_child_links[$j]['name'])) > 8 ?
							substr(Include_special_characters($nav_bar_child_links[$j]['name']), 0, 8).'..' : Include_special_characters($nav_bar_child_links[$j]['name']);

						$child_links_index_array++;
						?>
								<a class="nav_bar_links" style="cursor:pointer;" onclick="S_datas(<?php echo "'",$nav_bar_child_links[$j]['path'], "','"
										,$nav_bar_child_links[$j]['id'],"','",$nav_bar_child_links[$j]['name'],"','",$nav_bar_child_links[$j]['privilege'], "'"; ?>)">
						<?php
						echo   '<p class="nav_bar_paragraph_child" id="parent_id_'.$nav_bar_child_links[$j]['parent_id'].'">
										'.$child_name.'
									'.($nav_bar_child_links[$j]['ikon'] != 'none' ? '<img src="'.$nav_bar_child_links[$j]['ikon'].'" class="header_button_img" style="width: 20px;">' : '').'
									</p>
								</a>';
					}
				}
				echo 	'</div>';

				array_push($parent_links_index_array, array($nav_bar_links[$i]['id'] , $child_links_index_array));
			}
			array_push($num_of_non_child_item , $i);
		}
		else
		{
			////////////////////////
			#	Ha nem szülő	   #
			////////////////////////
			if($privilege == 3 || $nav_bar_links[$i]['privilege'] <= $privilege ){

				if($nav_bar_links[$i]['privilege'] > $privilege){

					echo    '<p class="nav_bar_paragraph" id="id_'.$nav_bar_links[$i]['id'].'" data-toggle="tooltip" title="Access denied">'
					,$name,
					($nav_bar_links[$i]['ikon'] != 'none' ? '<img src="'.$nav_bar_links[$i]['ikon'].'" class="header_button_img" style="width: 20px;">' : '').'
							</p>';
				}
				else{

					echo    '<a class="nav_bar_links" href="index.php'.$nav_bar_links[$i]['path'].'">
								<p class="nav_bar_paragraph" id="id_'.$nav_bar_links[$i]['id'].'">
									'.$name.'
						'.($nav_bar_links[$i]['ikon'] != 'none' ? '<img src="'.$nav_bar_links[$i]['ikon'].'" class="header_button_img" style="width: 20px;">' : '').'
								</p>
							</a>';
				}
			}
		}
		echo		'</div>';
	}
}
for($i = 0; $i < count($parent_links_index_array); $i++){

	echo '
	<style>
	#nav_bar_child_links_content_'.$parent_links_index_array[$i][0].'{
			display: none;
		border-bottom-right-radius: 10px;
		 border-bottom-left-radius: 80px;
						  border-right: 1px solid #986;
									width: 200px;
								  height: ', $parent_links_index_array[$i][1] * 45 + 10 ,'px;
								position: absolute;
								z-index: 10001;
									 left: 130px; calc(50% - 670px);
									  top: calc(75px + ', $num_of_non_child_item[$i]*43 ,'px);

	}
	#nav_bar_child_links_container_'.$parent_links_index_array[$i][0].':hover #nav_bar_child_links_content_'.$parent_links_index_array[$i][0].',
	#nav_bar_child_links_content_'.$parent_links_index_array[$i][0].' a {

								 display: block;
								 z-index: 10001;
	}
	</style>';
}

LogLn(1, "[NAVBAR][GETMENUS] *** END ***");
?>
</div>
<div id="nav_bar_results" >
	<b style="text-align: center;">

		<?php


			$was_eighty_percent = 0;
			$was_under_than_fifty_percent=0;
			$manual = 0; 	//???

			if(isset($_SESSION['user_datas']['id']) && $_SESSION['user_datas']['id'] != ''){

				$sql_time	='select
								substr(SEC_TO_TIME(ceil(sum(time_length) / 1000)), 4, 7) as "last_day",
								(select
									substr(SEC_TO_TIME(ceil(sum(time_length) / 1000)), 4, 7)
								from n_back_sessions
								where user_id = "'.$_SESSION['user_datas']['id'].'"
								and timestamp > current_date) as "today",
								(select
									substr(SEC_TO_TIME(ceil(sum(time_length) / 1000)), 4, 7)
								from `n_back_sessions`
								where `user_id` = "'.$_SESSION['user_datas']['id'].'"
								and `timestamp` > current_date
								and `manual` = 0) as "today_position"
							from n_back_sessions
							where user_id = "'.$_SESSION['user_datas']['id'].'"
							and timestamp >"'.date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'))).'";';

// 	'select
// 	concat( case when floor(sum(time_length) / 1000 / 3600) > 0 then concat(floor(sum(time_length) / 1000 / 3600),":") else "" end,
// 	case when floor((sum(time_length) / 1000 % 3600) / 60) > 0 then
// 	case when LENGTH(floor( (sum(time_length) / 1000 % 3600) / 60)) < 2 then concat("0", floor( (sum(time_length) / 1000 % 3600) / 60))
// 	else floor( (sum(time_length) / 1000 % 3600) / 60) end else "00" end, ":",
// 	case when floor((sum(time_length) / 1000 % 3600) % 60) > 0 then
// 	case when LENGTH(floor((sum(time_length) / 1000 % 3600) % 60)) < 2 then concat("0", floor((sum(time_length) / 1000 % 3600) % 60)) else
// 	floor((sum(time_length) / 1000 % 3600) % 60) end else "00" end) as "last_day",
// 	(select
// 	concat( case when floor(sum(time_length) / 1000 / 3600) > 0 then concat(floor(sum(time_length) / 1000 / 3600),":") else "" end,
// 	case when floor((sum(time_length) / 1000 % 3600) / 60) > 0 then
// 	case when LENGTH(floor( (sum(time_length) / 1000 % 3600) / 60)) < 2 then concat("0", floor( (sum(time_length) / 1000 % 3600) / 60))
// 	else floor( (sum(time_length) / 1000 % 3600) / 60) end else "00" end, ":",
// 	case when floor((sum(time_length) / 1000 % 3600) % 60) > 0 then case
// 	when LENGTH(floor((sum(time_length) / 1000 % 3600) % 60)) < 2 then concat("0", floor((sum(time_length) / 1000 % 3600) % 60)) else
// 	floor((sum(time_length) / 1000 % 3600) % 60) end else "00" end)
// 	from n_back_sessions
// 	where user_id = "'.$_SESSION['user_datas']['id'].'"
// 	and timestamp > current_date) as "today"
// 	from n_back_sessions
// 	where user_id = "'.$_SESSION['user_datas']['id'].'"
// 	and timestamp >"'.date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'))).'";';
// + 	and ip = "'.$_SERVER['REMOTE_ADDR'].'" for "today" , 	and ip = "'.$_SERVER['REMOTE_ADDR'].'".

	$SQL_sessions="
	select case when timestamp < current_date then concat('Yesterday ', substr(timestamp,12, 5)) else substr(timestamp, 12, 5) end as
	time,result, wrong_hit,correct_hit, level, manual, ip from n_back_sessions where user_id='".$_SESSION['user_datas']['id'] ."'
	and timestamp > '".date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y')))."'  order by timestamp desc limit 10";
			}
			else{

				$sql_time ='select
								SEC_TO_TIME(ceil(sum(`time_length`) / 1000)) as "last_day",
								(select
									SEC_TO_TIME(ceil(sum(`time_length`) / 1000))
								from `n_back_sessions`
								where `user_id` = 0000001
								and `timestamp` > current_date) as "today",
								(select
									SEC_TO_TIME(ceil(sum(`time_length`) / 1000))
								from `n_back_sessions`
								where `user_id` = 0000001
								and `timestamp` > current_date
								and `manual` = 0) as "today_position"
							from `n_back_sessions`
							where `user_id` = 0000001
							and `timestamp` >"'.date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'))).'";';

				$SQL_sessions="
	select case when timestamp < current_date then concat('Yesterday ', substr(timestamp,12, 5)) else substr(timestamp, 12, 5) end as
	time,result, wrong_hit,correct_hit, level, manual, ip from n_back_sessions where user_id= '1'
	and timestamp > '".date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y')))."'  order by timestamp desc limit 10";
			}


			LogLn(1, "[NAVBAR][GETGAMESTIME]***START***");
				$row=Sql_query($sql_time);
			LogLn(1, "[NAVBAR][GETGAMESTIME] ***END***");

			echo "<h4 class='nav_bar_text'>The total time of sessions in the last 24 hours:</h4><h5>".($row[0]['last_day'] == '' ? '0' : $row[0]['last_day'])." min</h5>";
	
			#it's working only under 1 hour, cause over 1 hour the conversation run onto false!! 
			echo "<h4 class='nav_bar_text' >Total duration of today's games:</h4><h5><span ", explode(":",$row[0]['today_position'])[0] >= 20 ? ' id="goal_label" ' : "", " >", $row[0]['today'] == '' ? '0' : $row[0]['today'], "</span> min</h5>
 			<h4 class='nav_bar_text'>Last 10 sessions:</h4>";

			LogLn(1, "[NAVBAR][GETSESSIONSDATAS]***START***");
				$row=Sql_query($SQL_sessions);
			LogLn(1, "[NAVBAR][GETSESSIONSDATAS] ***END***");




			for($i=0; $i< sizeof($row); $i++){
				if($row[$i]['correct_hit'] != '0' ){
				////////////////////////////////////////////////////////////////////////////////////////////
				//	Az eredmények kiírásáért felelős rész. Ha nagyobb mint 80% legyen az érték színe zöld.//
				//	Ha kisebb 50 legyen piros, egyébként fekete.										  //
				//  Nem jó! ha csak az utolsó 24 órát vizsgálok fals az eredmény.						  //
				////////////////////////////////////////////////////////////////////////////////////////////
					if(round($row[$i]['correct_hit'] / (($row[$i]['wrong_hit'] + $row[$i]['correct_hit']) / 100)) >= 80){
						echo '<h6>End at: ', $row[$i]['time'], ',<br> <span id="good_trial_nav_not_font">'.round($row[$i]['correct_hit'] / (($row[$i]['wrong_hit'] + $row[$i]['correct_hit']) / 100)).'%</span>, level: ', $row[$i]['level'],', ', ($row[$i]['manual'] == 1) ? 'manual' : 'position', '</h6>';
					}
					else if(round($row[$i]['correct_hit'] / (($row[$i]['wrong_hit'] + $row[$i]['correct_hit']) / 100)) <= 50){
						echo '<h6>End at: ', $row[$i]['time'], ',<br> <span id="bad_trial_nav_not_font">'.round($row[$i]['correct_hit'] / (($row[$i]['wrong_hit'] + $row[$i]['correct_hit']) / 100)).'%</span>, level: ', $row[$i]['level'],', ', ($row[$i]['manual'] == 1) ? 'manual' : 'position', '</h6>';
					}
					else{
						echo '<h6>End at: ', $row[$i]['time'], ',<br>'.round($row[$i]['correct_hit'] / (($row[$i]['wrong_hit'] + $row[$i]['correct_hit']) / 100)).'%, level: ', $row[$i]['level'],', ', ($row[$i]['manual'] == 1) ? 'manual' : 'position', '</h6>';
					}
				}
				else{
					echo '<h6>End at: ', $row[$i]['time'], ',<br><span id="bad_trial_nav_not_font">', str_repeat('&nbsp;', 2), '0%</span>, level: ', $row[$i]['level'],', ', ($row[$i]['manual'] == 1) ? 'manual' : 'position', '</h6>';
				}
				if($row[$i]['manual'] == '0'){
					#n-back increase
					if($row[$i]['result'] == 1){
						$was_eighty_percent++;
						if($was_eighty_percent==2){
							#cookie
							if(isset($_COOKIE['level'])){
								setcookie ('level',settype($_COOKIE['level'], 'integer') + 1);
							}
							elseif(isset($_SESSION['user_datas']['level'])){
								setcookie ('level',settype($_SESSION['user_datas']['level'], 'integer') +1);
							}
							else{
								setcookie ('level',1);
							}
							#SESSION / Csak ha van felhasználó!
							if(isset($_SESSION['n_back_datas']['level'])){

								$_SESSION['n_back_datas']['level']++;
								/*Update*/
								LogLn(1, "[NAVBAR][SETLEVELANDRESULT][START]");

								Sql_execute_query('update n_back_datas set level = '.$_SESSION['n_back_datas']['level'].', trials = "'.($_SESSION['n_back_datas']['trials'] + 5).'"  where user_id = '.$_SESSION['user_datas']['id'].'; ');
								Sql_execute_query('update n_back_sessions set result = "0" where user_id = '.$_SESSION['user_datas']['id'].';');

								LogLn(1, "[NAVBAR][SETLEVELANDRESULT][END]");
								Load_datas();
							}
									#Ha nincs felhasználó
							else{
								Sql_execute_query('update n_back_sessions set result = "0" where user_id = "1" and ip = "'.$_SERVER['REMOTE_ADDR'].'";');
							}
							header('Location: index.php?index=9&rep_common=4');
						}
					}
					#n-back decrease
					elseif ($row[$i]['result'] == -1){
						$was_under_than_fifty_percent++;
						if($was_under_than_fifty_percent == 3){
							#cookie
							if(!isset($_COOKIE['level'])){
								if(isset($_SESSION['n_back_datas']['level'])){
									setcookie('level', $_SESSION['n_back_datas']['level']);
								}
								else{
									setcookie('level', '1');
								}
							}
							#SESSION / Csak ha van felhasználó!
							if(isset($_SESSION['n_back_datas']['level'])) {
								if($_SESSION['n_back_datas']['level'] != '1'){
									$_SESSION['n_back_datas']['level']--;
									/*Update*/
									LogLn(1, "[NAVBAR][SETLEVEL][START]");
									Sql_execute_query('update n_back_datas set level = "'.$_SESSION['n_back_datas']['level'].'" , trials = "'.($_SESSION['n_back_datas']['trials'] - 5).'" where user_id = "'.$_SESSION['user_datas']['id'].'"; ');
									LogLn(1, "[NAVBAR][SETLEVEL][END]");
								}
								Sql_execute_query($sql = 'update n_back_sessions set result = "0" where user_id = "'.$_SESSION['user_datas']['id'].'" and ip = "'.$_SERVER['REMOTE_ADDR'].'";');
								Load_datas();
							}
									#Ha nincs felhasználó
							elseif( $_COOKIE['level'] !=1 ){
								Sql_execute_query('update n_back_sessions set result = "0" where user_id = "1" and ip = "'.$_SERVER['REMOTE_ADDR'].'";');
							}
							header('Location: index.php?index=9&rep_common=3');
						}
					}
				}
			}
		?>
	</b>
</div>
<style>
<?php
	for($i=0;$i<$was_under_than_fifty_percent; $i++){
		echo '#false_indcator_',$i,'{
			background-repeat: no-repeat;
			background-image: url("img/alert_red.png");
			height: 18px;
			width: 16%;
			float: left;
			margin: 3px;			
		}';
	}
	if($was_eighty_percent > 0){
		echo '#increase_indcator{
			background-repeat: no-repeat;
			 background-image: url("img/alert_green.png");
			  background-size: 17px 17px;
			           height: 18px;
			            width: 16%;
			            float: left;
			           margin: 3px;
		}';
	}
?>
</style>
</div>
<?php
	LogLn(1, "[NAVBAR] *** END *** ");
?>