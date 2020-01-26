<script>

<?php if($main_theme == 'black'){
	echo 'var theme = "black";';
}
?>
function Visible(){
	var e = document.getElementById("forum_img");
	e.style.visibility =  "visible";
	console.log(e);
}
function Modify_img_background(){

	if(theme = "black"){

		document.getElementById("forum_img").style.backgroundImage = "radial-gradient(white 40%, black)";
	}
}
</script>
<div id="create_forum_item">
<?php
if($_SESSION['user_datas']['privilege'] == 3){

	$case = $_GET['choose'];

	switch($case){

	case '0':{
				echo '<h2 style="text-align:center">Forums</h2>';

				$menus_colums = Sql_query('SHOW COLUMNS FROM menus');

				echo '<table id="edit_forum_table">';

					echo '<tr>';
						echo '<th>Number</th>';
						echo '<th>', $menus_colums[1]['Field'] ,'</th>';
						echo '<th>', $menus_colums[2]['Field'] ,'</th>';
						echo '<th>', $menus_colums[4]['Field'] ,'</th>';
						echo '<th>', $menus_colums[5]['Field'] ,'</th>';
						echo '<th>Logs</th>';
						echo '<th></th>';
						echo '<th></th>';

					echo '</tr>';




				$sql_get_menus = 'SELECt m.id, m.name, (select name FROM menus WHERE id = m.parentID) as parent, m.ikon, m.privilege, (SELECT count(*) FROM logs where menuID = m.id) as logs FROM menus as m WHERE parentID = 00000001 and name != "Edit"';
				$get_menus = Sql_query($sql_get_menus);

				for($i=0;$i<count($get_menus); $i++){

				$ikon = $get_menus[$i]['ikon'] == 'none' ? 'None' : $get_menus[$i]['ikon'];

				$menu_item_name = Include_special_characters($get_menus[$i]['name']);

					echo '<tr>';
					echo '<td>', $i + 1,'</td>';
					echo '<td>',$menu_item_name ,'</td>';
					echo '<td>', $get_menus[$i]['parent'],'</td>';
					echo '<td>', ($ikon == 'None' ? 'None' : '<img id="forums_list_img" src="'.$ikon.'" style="width: 25px;'.($main_theme == "black" ? 'background-image: radial-gradient(white 10%, black);' : '').'">'),'</td>';
					echo '<td>', $get_menus[$i]['privilege'],'</td>';
					echo '<td>', $get_menus[$i]['logs'],'</td>';
					echo '<td class="special_td"><a href="#" onclick="Load_menu_datas_to_form('."'".$get_menus[$i]['name']."','".$get_menus[$i]['id']."','".$get_menus[$i]['privilege']."','".$ikon."'".'); Modify_img_background();"'.'>Modify</a></td>';
					echo '<td class="special_td"><a href="index.php?index=10&choose=1&id='.$get_menus[$i]['id'].'&ikon='.$get_menus[$i]['ikon'].'">Delete</a></td>';
					echo '</tr>';

				}
				?>
				</table>


				<form action="index.php?index=10&choose=2" method="POST" enctype="multipart/form-data">
					<div id="create_forum_form_text_container">Create forum</div>
						<input type="text" placeholder="Name" id="create_forum_form_text_container_name_input" name="create_forum_name" required  autocomplete="off">
						<input type="text" placeholder="Privilege" id="create_forum_form_text_container_privilege_input"	name="create_forum_privilege" required  autocomplete="off">

						<label for="edit_user_file" class="file_lbl" id="edit_user_file_lbl" style="font-size: 14px;" >
							<input type="text" name="max_filesize" value="200000" readonly hidden>
							<input  id="edit_user_file" accept="image/*" value="None" type="file" name="file" class="inputfile" onchange="read_url(this, '#forum_img'); Visible();Modify_img_background();" >
							<span id="file_font_to_js">Upload Ikon</span>
						</label>



						<input type="text" name="modify_forum_items_id" id="create_forum_form_text_container_id_input" value="" hidden readonly>

						<img id="forum_img" />
						<button type="submit" name="create_forum_room_submit"  id="create_forum_room_submit" value="Create"></button>
					<div class="clear"></div>
				</form>


				<?php

			} break;

	case '1':{

			$sql_del_menu_item = 'DELETE FROM menus WHERE id = '.$_GET['id'];
			if(Sql_execute_query($sql_del_menu_item) == 0){

				echo '<h2 style="text-align: center;">Delete successful</h2>';
			}
			else{

				echo '<h2 style="text-align: center;">Delete failed</h2>';
			}

			if(file_exists($_GET['ikon'])){
				unlink($_GET['ikon']);
			}
 			header('refresh: 1; url=index.php?index=10&choose=0');

			}break;

	case '2' :{

 			$path =  'img/forum_ikons' ;

 			$escaped_forum_name = Escape_special_characters($_POST['create_forum_name']);
			$path_fileName = $_FILES['file']['name'] != '' ?  $path.'/'.$escaped_forum_name . '_'. $_FILES['file']['name'] : 'none';
			$privilege = $_POST['create_forum_privilege'] != '' ?  $_POST['create_forum_privilege'] : '1';
			$name = $_POST['create_forum_name'] != '' ?  $_POST['create_forum_name'] : 'Anonimus room';





 			if($_POST['modify_forum_items_id'] != 0){
				if($path_fileName == 'none'){
					$sql_menu_item = 'Update menus SET name="'.$escaped_forum_name.'", privilege="'.$privilege.'" WHERE id = "'.$_POST['modify_forum_items_id'].'";';
				}
				else{
					$sql_menu_item = 'Update menus SET name="'.$escaped_forum_name.'", privilege="'.$privilege.'", ikon ="'.$path_fileName.'" WHERE id = "'.$_POST['modify_forum_items_id'].'";';
				}
			}
			else{
				$sql_menu_item =
				'INSERT INTO menus (name,parentID, path, ikon, privilege, child) VALUES ("'.$escaped_forum_name.'","00000001","?index=7&offset=0",
				"'.$path_fileName.'","'.$_POST['create_forum_privilege'].'","0");';
			}

			if(Sql_execute_query($sql_menu_item) == 0){

				$sql_get_file = 'SELECT ikon FROM menus WHERE id ='.$_POST['modify_forum_items_id'].';';
				if($_POST['modify_forum_items_id'] != '' && isset($_FILES['file']['name']) && $ikon = Sql_query($sql_get_file)[0]['ikon'] != 'none'){

					if(file_exists($ikon)){

						unlink($ikon);
					}
				}


				#sudo chown -R www-data:m_ /var/www/html/mnback.com/sd/img  - Jogosultság
				if(!is_dir($path)){
					$oldmask = umask(0);
					mkdir($path, 0777 /*00777 teljes jog*/, true);
					umask($oldmask);
				}


				if($path_fileName != 'none'){
					if ( !file_exists($path_fileName)) {

						move_uploaded_file($file["file"]["tmp_name"], $path_fileName);
						echo '<h2 style="text-align: center;">Upload is successful</h2>';

					}
					else{
						echo '<h2 style=" text-align: center;">',$path_fileName . ' already exists. </h2>';
					}
				}

			}
			else{
				echo '<h2 style=" text-align: center;">Download faild</h2>';
			}

    			header('refresh: 1; url=index.php?index=10&choose=0');

			} break;
	}
}
else
header('Location: index.php');
?>
</div>
