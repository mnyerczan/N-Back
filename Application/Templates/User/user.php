<?php

if(isset($_POST['update']))
{
	$user_case ='3';
}
elseif(isset($_POST['del_img']) && $_POST['del_img'] != 'none')
{
	$user_case = '4';
}
elseif(!isset($_POST['id']) && !$user->id == 1 || isset($_GET['c']))
{
	$user_case ='1';
}
else $user_case ='2';


if(isset($_POST['id']) && $_POST["id"] != '')
{

	$sql_get_user_datas =
	'SELECT
	u.name,
	u.id,
	u.birth,
	u.email,
	u.loginDatetime,
	u.userName,
	u.passwordLength,
	u.privilege,
	u.fileName,
	u.password,
	nb.level,
	nb.gameMode
	FROM nbackDatas nb, users u WHERE u.id = "'.$_POST['id'].'" and u.id = nb.userID;';

	$result_user = Sql_query($sql_get_user_datas);
}
else{

	$result_user[0]['level'] = $user->level;
	$result_user[0]['seconds'] = $user->seconds;
	$result_user[0]['trials'] = $user->trials;
	$result_user[0]['eventLength'] = $user->eventLength;
	$result_user[0]['color'] = $user->color;

	$result_user[0]['name'] = $user->name;
	$result_user[0]['birth'] = $user->birth;
	$result_user[0]['email'] = $user->email;
	$result_user[0]['loginDatetime'] = $user->loginDatetime;
	$result_user[0]['userName'] = $user->userName;
	$result_user[0]['passwordLength'] = $user->passwordLength;



	$result_user[0]['gameMode'] = $user->gameMode;
	$result_user[0]['level'] = $user->level;

}
var_dump($result_user);
?>
<form method="POST" id="post_form"  hidden>
	<input name="id" id="id" type="text" hidden readonly>
	<input name="modify" id="modify" type="text" value="none" hidden readonly>
</form>

<script>

var id = "<?php echo $result_user[0]['id']; ?>";

function delete_img_from_srv(){
	document.getElementById("img_userID").value = id;
	document.getElementById("del_img").value = 1;
	document.getElementById("delete_user_profile_image").action="index.php?index=6";
	document.getElementById("delete_user_profile_image").submit();
}
</script>
<form method="POST" id="delete_user_profile_image"  hidden>
	<input name="img_userID" id="img_userID" type="text" hidden />
	<input name="del_img" id="del_img" type="text" value="none" hidden />
</form>
<?php


switch($user_case){

	case '1':{
		//////////////////////////////////////////////////////////////////////////////¨//
		//		Create user																//
		//////////////////////////////////////////////////////////////////////////////_//
		?>
			
	<?php
	} break;


	case '2':{

		if(count($result_user) > 0 && !isset($_POST['modify']) || $_POST['modify'] == 'none'){
			//////////////////////////////////////////////////////////////////////////////
			//	user profile															//
			//////////////////////////////////////////////////////////////////////////////
			?>
			<script>
				id="<?php if(isset($_POST['id'])) echo $_POST['id']; else echo $_SESSION['user_datas']['id'] ?>";

				function Step_to_modify_user(){
					document.getElementById("id").value=id;
					document.getElementById("modify").value= "1";
					document.getElementById("post_form").submit();
				}
				function Confirm_delete_user(){

					var conf = confirm("Do you want?");
					if(conf == true){
						console.log("#");
						document.getElementById("id").value=id;
						document.getElementById("modify").value= "none";
						document.getElementById("post_form").action = "index.php?case=user_del";
						document.getElementById("post_form").submit();
					}
				}
			</script>

			<div id="show_user_div">
				<table>
					<tr>
						<td class="td_label"><span>Name:</span> </td>
						<td><?=Include_special_characters($result_user[0]['name'])?></td>
					</tr>
					<tr>
						<td class="td_label"><span>Date of birth:</span></td>
						<td><?=$result_user[0]['birth']?></td>
					</tr>
					<tr>
						<td class="td_label"><span>E-mail address: </span></td>
						<td><?=$result_user[0]['email']?></td>
					</tr>
					<tr>
						<td class="td_label"><span>Registration date: </span></td>
						<td><?=$result_user[0]['loginDatetime']?></td>
					</tr>
					<tr>
						<td class="td_label"><span>Login name: </span></td>
						<td><?=Include_special_characters($result_user[0]['userName'])?></td>
					</tr>
					<tr>
						<td class="td_label"><span>Password: </span></td>
						<td>
						<?php for($i=0; $i< $result_user[0]['passwordLength']; $i++):?> 
							*						
						<?php endfor ?>
						</td>
					</tr>
					<?php if($user->privilege > 2):?>
					<tr>
						<td  class="td_label"><span>Privilege: </span></td>
						<td><?=$result_user[0]['privilege']?></td>
					</tr>					
					<tr>
						<td class="td_label"><span>N-Back Level: </span></td>
						<td><?=$result_user[0]['level']?></td>
					</tr>
					<tr>
						<td  class="td_label"><span>Position mode: </span></td>
						<td><?=$result_user[0]['gameMode']?></td>
					</tr>
					<tr>
						<td  class="td_label"><span>Progression in last 7 days:</span></td>
						<td>
				<?php if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][USERS][GETUSERSGAMESTAT]***START***\n" , $log_param_1);
				$sql_get_last_week_stat = '
	select
	concat(round(100 -(select (sum(level) / count(level)) + (sum(correctHit) /( sum(correctHit) + sum(wrongHit))) as cw_stat from nbackSessions
	where timestamp < DATE_ADD(curdate(), INTERVAL -6 DAY) and timestamp > DATE_ADD(curdate(), INTERVAL -14 DAY)  and userID = '.$userID.')
	/
	(select (sum(level) / count(level)) + (sum(correctHit) /( sum(correctHit) + sum(wrongHit))) as cw_stat from nbackSessions
	where timestamp >DATE_ADD(curdate(), INTERVAL -6 DAY) and userID = '.$userID.')
	*100), " %") as stat;';

				$last_week_stat = Sql_query($sql_get_last_week_stat)[0]['stat'];

				if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][USERS][GETUSERSGAMESTAT] ***END***\n" , $log_param_1);
				if ($last_week_stat != NULL) echo $last_week_stat;
				else echo 'No datas';?>
				</td>
					</tr>
				</table>
						<img id="profil_pics" 
				<?= ((isset($result_user[0]['fileName']) && $result_user[0]['fileName'] != 'none') ?
				'src="users/'. $result_user[0]['id']. '/'.Include_special_characters($result_user[0]['fileName']).'" ' : 'src="img/default_user_black.png'.'"')?> >

			</div>

			<div id="n_back_well_come_navigation_container">
				<br>
					<button class="button" id="header_user_modify_button" onclick="Step_to_modify_user()" style="cursor:pointer" data-toggle="tooltip" title="Modify datas" >
					</button>
				<br>'
				?>
				<a  onclick="Confirm_delete_user()">
					<button class="button" id="header_user_delete_button" data-toggle="tooltip" title="Delete profile" >
					</button>
				</a>

			</div>
			<div class="clear"></div>

			<?php Require_once('chart.php'); ?>

		}
		else{

				////////////////////////////////////////////////////////////////////////////////
				//	update user profil														  //
				////////////////////////////////////////////////////////////////////////////////

				?>
				<script>
					function Back_to_profile(){
						document.getElementById("id").value = <?php echo $result_user[0]['id'];?>;
						document.getElementById("post_form").action="index.php?index=6";
						document.getElementById("post_form").submit();
					}
				</script>
				<?php
				echo '
				<div id="edit_user_div">
					<form enctype="multipart/form-data" id="edit_user_form" name="edit_user_form" method="POST" action="index.php?index=6" autocomplete="off" onsubmit="return Check(true);">
						<table>
							<tr>
								<td class="td_label"><span>Name: </span></td>
								<td>'.Include_special_characters($result_user[0]['name']).'</td>
							</tr>
							<tr>
								<td class="td_label"><span>Date of birth: </span></td>
								<td>'.$result_user[0]['birth'].'</td>
							</tr>
							<tr>
								<td class="td_label"><span>E-mail: </span></td>
								<td><input name="mail" type="email" placeholder="'.$result_user[0]['email'].'"/></td>
							</tr>
							<tr>
								<td class="td_label"><span>Registration date: </span></td>
								<td>'.$result_user[0]['loginDatetime'].'</td>
							</tr>
							<tr>
								<td class="td_label"><span>Login name: </span></td>
								<td>
									<input name="userName" type="text" autocomplete="off" placeholder="'.Include_special_characters($result_user[0]['userName']).'" >
									<input name="id" type="text" value="'.$result_user[0]['id'].'"  readonly hidden>
								</td>
							</tr>
							<tr>
								<td class="td_label"><span>Password: </span></td>
								<td><input name="pass" type="password" autocomplete="off" placeholder="';
									for($i=0; $i< $result_user[0]['passwordLength']; $i++) echo '*';
							echo '" minlength="4"></td>
									</tr><tr>';
							if(isset($_POST['id']) && isset($_SESSION['user_datas']['id']) && $_POST['id'] !=  $_SESSION['user_datas']['id']  && $_SESSION['user_datas']['privilege'] > 2)
							echo '<td class="td_label"><span>Privilege: </span></td>
								<td>
								<div id="edit_user_radio_container">
									<label class="radio_container" style="pointer-events:auto;">
										<input form="edit_user_form" type="radio" class="set_user_privilege_radio" name="privilege" value="1" '.($result_user[0]['privilege'] == '1' ? 'checked' : '').'>1
										<span class="circle" ><span>
									</label>
									<label class="radio_container" style="pointer-events:auto;">
										<input form="edit_user_form" type="radio" class="set_user_privilege_radio" name="privilege" value="2" '.($result_user[0]['privilege'] == '2' ? 'checked' : '').'>2
										<span class="circle"><span>
									</label>
									<label class="radio_container" style="pointer-events:auto;">
										<input form="edit_user_form" type="radio" class="set_user_privilege_radio"  name="privilege" value="3" '.($result_user[0]['privilege'] == '3' ? 'checked' : '').'>3
										<span class="circle"><span>
									</label>
								</div>
							</td>
						</tr>';

						echo '</table>
						<input name="mail_original" type="mail" value="'.$result_user[0]['email'].'" hidden/>
						<input name="passwordLength_original" type="text" value="'.$result_user[0]['passwordLength'].'" hidden/>
						<input name="userName_original" type="text"  value="'.Include_special_characters($result_user[0]['userName']).'" hidden>
						<input name="pass_original" value="'.$result_user[0]['password'].'"  hidden>
						<input name="update" value="1" type="text" form="edit_user_form" hidden>
					</form>
				</div>
				<div style="margin: auto;">
					<div style="float: right; width: 200px; padding: 20px; display: block;">'.
					((!isset($result_user[0]['fileName']) || $result_user[0]['fileName'] == 'none')
						?
					'
						<div class="wrap" style="pointer-events: auto;">
							<div id="file_text" style="width: 150px;"></div>
							<label for="edit_user_image" class="file_lbl" id="edit_user_file_lbl" style="font-size: 14px">
								<input type="text" name="max_filesize" value="200000" readonly hidden>
								<input form="edit_user_form" name="file" type="text" value="'.Include_special_characters($result_user[0]['fileName']).'" readonly hidden>
								<input form="edit_user_form" id="edit_user_image" accept="image/*" type="file" name="file" class="inputfile" onchange="read_url(this, '.(true ? "'#user_img'" : '').');">
								Select image
							</label>
						</div>'
							:
						'<div class="wrap" style="pointer-events: auto; cursor:pointer"  onclick="delete_img_from_srv()">
							<div id="file_text"></div >
							<a id="edit_user_image" style="_width: 100px; padding: 10px 30px 20px 30px;">
								Delete image
							</a>
						</div>'
						).
					'</div>
					<div id = "user_img_nav_container">

						<img id="user_img" style=" width: 200px;" '.
					((isset($result_user[0]['fileName']) && $result_user[0]['fileName'] != 'none') ?
						' src="users/'. $result_user[0]['id']. '/'.Include_special_characters($result_user[0]['fileName']).'" style="width: 300px;"' :
						'src="img/default_user_black.png"'
						).' >
					</div>
					<br>
					<div id="n_back_well_come_navigation_container">
						<button type="submit" form="edit_user_form" class="button" id="edit_user_submit"
							name="edit_user_submit" value="Ok"  data-toggle="tooltip" title="Save">
						</button>
						<a onclick="Back_to_profile()">
							<button class="button" id="edit_user_back"  data-toggle="tooltip" title="Back">
							</button>
						</a>
					</div>

				</div>
				<div style="width: 200px;height:50px; position:relative; top: -100px; left: 463px;">
						<p id="user_modify_error_label"><p>
				</div>';

			}
		}break;
		case 3:{
			Modify_user_datas();

			if($error_level < 4)	echo '
				<script>
					document.getElementById("id").value = "'.$result_user[0]['id'].'";
					document.getElementById("post_form").action = "index.php?index=6";
 					document.getElementById("post_form").submit();
				</script>
			';
		}break;
		case 4:{

			Delete_image_from_server();

			if($error_level < 4)	echo '
			<script>
				document.getElementById("id").value = "'.$result_user[0]['id'].'";
				document.getElementById("post_form").action = "index.php?index=6";
				document.getElementById("post_form").submit();
			</script>
			';

	}break;
// 	default: header('Location: index.php');

}
if(isset($_POST['modify']) && $_POST['modify'] != 'none') echo
			'<script>

			function Check(is_privilege){


				var privilege = "'.$result_user[0]['privilege'].'";

				if(is_privilege){
					boolen = parseInt(document.forms["edit_user_form"]["privilege"].value) == parseInt(privilege) ? true : false;
				}
				else boolen = true;

				if( document.getElementById("edit_user_image").value == null  && boolen){

					if( document.forms["edit_user_form"]["mail"].value.length < 1  &&
						document.forms["edit_user_form"]["userName"].value.length < 1 &&
						document.forms["edit_user_form"]["pass"].value.length < 1){

						document.getElementById("user_modify_error_label").innerHTML = "Fill at least one field!";
						return false;
					}
					else if(document.forms["edit_user_form"]["pass"].value.length > 1 &&
						document.forms["edit_user_form"]["pass"].value.length < 4)
					{
						document.getElementById("user_modify_error_label").innerHTML = "The password is at least four characters.";
						return false;
					}
				}
				else{
					return true;
				}

			}
			</script>';
?>

