<?php
if (isset($_COOKIE['correctHit'])) $was_session = true; else $was_session = false;

if(!isset($_SESSION['nbackDatas']['gameMode'] ) || $_SESSION['nbackDatas']['gameMode'] == ""){
    $_NBACK['gameMode'] 			= isset($_COOKIE['gameMode']) ? $_COOKIE['gameMode'] : 'Off';
    $_NBACK['level']			= isset($_COOKIE['level']) ? $_COOKIE['level'] : '1';
    $_NBACK['seconds']			= isset($_COOKIE['seconds']) ? $_COOKIE['seconds'] : '3';
    $_NBACK['trial']			= isset($_COOKIE['trial']) ? $_COOKIE['trial'] : 20 + 5;
    $_NBACK['eventLength']		= isset($_COOKIE['eventLength']) ? $_COOKIE['eventLength'] : '0.500';
    $_NBACK['color']			= isset($_COOKIE['color']) ? $_COOKIE['color'] : 'blue';
    $trial_min 					= isset($_COOKIE['level']) ? $_COOKIE['level'] * 5 : 5;
}
else
{
    $_NBACK['gameMode']  			= $_SESSION['nbackDatas']['gameMode'];
    $_NBACK['level']			= $_SESSION['nbackDatas']['level'];
    $_NBACK['seconds']			= $_SESSION['nbackDatas']['seconds'];
    $_NBACK['trial']			= $_SESSION['nbackDatas']['trials'] ;
    $_NBACK['eventLength']		= $_SESSION['nbackDatas']['eventLength'];
    $_NBACK['color']  			= $_SESSION['nbackDatas']['color'];
    $trial_min 					= $_SESSION['nbackDatas']['level'] * 5;
}


$nb_case =  (isset($_GET['nb_common'])) ? $_GET['nb_common'] : '';
switch($nb_case){

	case 1:{

		?>
		<script>
			var place_index = parseInt("<?php echo rand(0, 8) ?>"),
				number_of_visiblility = 0,
				level = parseInt("<?php echo $_NBACK['level'] ?>"),
				seconds_js = parseInt("<?php echo $_NBACK['seconds']; ?>"),
				correctHit = 0,
				wrongHit = 0;
				length_of_session =  parseInt("<?php echo $_NBACK['trial'];?>"),
				eventLength = parseFloat("<?php echo $_NBACK['eventLength']; ?>"),
				start_timestamp = new Date().getTime(),
				help_timestamp = start_timestamp;				
				gameMode = "<?php  echo ($_NBACK['gameMode'] == 'gameMode') ? 'gameMode' : 'Position mode:';?>",
				is_escape = 0,
				n_back_notice_1_help = 0,
				num_of_seccons = 0,
				place_index = 4,
				n_back_session_sessionLength = 0,
				t = [],
				array_indexes = [];

			document.getElementById("info_correct").innerHTML = 0;
			document.getElementById("info_wrong").innerHTML   = 0;
			document.getElementById("info_percent").innerHTML = "--";
			document.getElementById("info_time").innerHTML    = 0+" s";
		</script>
		<style>
			<?php echo ".n_back_visible{background-image: url('img/squares/spr_square_".$_NBACK['color'].".png');}" ?>
		</style>
		<div id='n_back_header'>
			<div type='text' class='n_back_input' id='n_back_notice_0' readonly style="font-size:20px;"></div>
			<input type='text' class='n_back_input' id='n_back_notice_1' placeholder="<?php echo $_NBACK['trial'].' back' ?>" readonly style="font-size:20px;">
		</div>
		<div id='n_back_margin'>
			<table class="n_back_table">
				<tr>
					<td ><div class="n_back_hidden" id='n_back_table_image_0' ></div></td>
					<td id="n_back_table_top_center_td" ><div class="n_back_hidden" id='n_back_table_image_1' ></div></td>
					<td><div class="n_back_hidden" id='n_back_table_image_2' ></div></td>
				</tr>
				<tr>
					<td id="n_back_table_center_left_td" ><div class="n_back_hidden" id='n_back_table_image_3' ></div></td>
					<td id="n_back_table_center_td"><p style="text-align:center;color:#666" >+</p></td>
					<td id="n_back_table_center_right_td" ><div class="n_back_hidden" id='n_back_table_image_5' ></div></td>
				</tr>
				<tr>
					<td><div class="n_back_hidden" id='n_back_table_image_6' ></div></td>
					<td id="n_back_table_bottom_center_td" ><div class="n_back_hidden" id='n_back_table_image_7' ></div></td>
					<td><div class="n_back_hidden" id='n_back_table_image_8' ></div></td>
				</tr>
			</table>
		</div>
		<div id="n_back_bottom_info_container">
			<span class="n_back_input" id="n_back_notice_2">A: position</span>
			<span id="n_back_notice_3">Exit: Esc</span>
		</div>
		<?php
	} break;

	case 2:{

		$limit = isset($_cookie_datas['limit_of_doc_items']) ? ($_cookie_datas['limit_of_doc_items'] >=1 ? $_cookie_datas['limit_of_doc_items'] : 1) : 25;
		$offset = isset($_get_datas['offset']) ? $_get_datas['offset'] : '0';
		$doc_count =  ceil(Sql_query('SELECT count(*) as count FROM documents;')[0]['count']);
		$get_query_count = isset($_GET['count']) ? $_GET['count'] : (isset($_SESSION['user_datas']['privilege']) && $_SESSION['user_datas']['privilege'] == 3 ? ceil($doc_count /$limit): ceil(($doc_count -1) /$limit));
		$sql_get_list_of_document = 'SELECT id, content, privilege, title FROM documents where title != "start_page" and privilege <= "'.(isset($_SESSION['user_datas']['privilege']) ? $_SESSION['user_datas']['privilege']  : '0').'" LIMIT '.$limit.' OFFSET '.($offset*$limit).';';
	?>
		<h1 style="text-align: center; font-size: 30px;">Documents</h1>
		<div class="main_page_center_medium_consider_container" >
			<div id="n_back_well_come_navigation_container" >
		<?php if(isset($_SESSION['user_datas']['id'])){
		echo
				'<div class="n_back_well_come_controls" >
						<a href="index.php?index=3&nb_common=4">
							<button tabindex="2"  name="n_back_info_create" value="1" id="n_back_info_create" title="create new document"></button>
						</a>
				</div>';
			}
		?>
				<div class="n_back_well_come_controls" >
					<a href="index.php?">
						<button tabindex="2"  name="n_back_info_back" value="1" id="n_back_options_back" title="Step back"></button>
					</a>
				</div>
			</div>
			<div id="num_of_items_container">
				Num of documents:
				<input type="text" name="num_of_items" id="num_of_items" placeholder="<?php echo $limit; ?>" style="width: 30px;" onkeydown="Was_enter_down_nb(event)" />
			</div>

			<form action="index.php?index=3&nb_common=5" id="hidden_form_for_docid" method="POST">
				<input type="text" id="doc_list_docid_input" name="docid" hidden value="">
			</form>
		<?php
			echo '<div style="padding: 9px; width: 150px; float: left;">',$offset +1, '. page / '.$get_query_count.'</div>';
			if(count($documents = Sql_query($sql_get_list_of_document)) >0){
				echo '<div id="documents_title_container"><ul>';

				foreach($documents as $doc){
					$document_title_in_list = strlen($doc['title']) > 0 ? $doc['title'] : '"No title"';
					if( $doc['privilege'] == 0  ||
						(isset($_SESSION['user_datas']['privilege']) && ($_SESSION['user_datas']['privilege'] >= $doc['privilege'] || $_SESSION['user_datas']['privilege'] == 3))){
						?>
							<li><?php echo $document_title_in_list; ?> <a href="#" onclick="Send_docid_from_list('<?php echo $doc['id']; ?>')">link</a></li>
						<?php
					}
				}
				echo '</ul></div>';
				if($get_query_count > 1){
		?>
				<div id="navigator_container">
					<div id="navigator_inside_container">
						<div >
							<img src="img/left_arrow_blue.png" class="n_back_info_navigation_img" onmousedown="N_back_page_back();"/>
							<img src="img/right_arrow_blue.png" class="n_back_info_navigation_img" style="float: right;" onmousedown="N_back_page_forward();"/>
						</div >
					</div>
				</div>
		<?php
				}
			}
			else{
				echo '<h2 style="text-align: center; margin-top: 100px;">Folder is empty</h2><div id="n_back_empty_folder_img_container"></div>';
			}
		?>
		</div>
		<?php
	} break;
	case 3:{
		?>
		<script type="text/javascript">
			this.gameMode = <?php if($_NBACK['gameMode'] == 'Off') {echo 'false;';} else {echo 'true;';}?>
			this.trial_min = <?php echo $trial_min; ?>;
			this.seconds_min = 0.1;

			function Key_event_on_nback_options(){
				if( document.activeElement.tabIndex  == 8)
					document.getElementById("n_back_options_form").focus();
			}
		</script>
		<style>
			body{
				overflow: hidden;
			}
		</style>
		<h1 style="text-align:center">Choose your game mode</h1>
		<div id="n_back_options">
		<!-- Names -->
		<div id="n_back_options_names">
			<div class="n_back_options_values_container">
				<b tabindex="-1" >Position mode</b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1" style="border-color: transparent"><u>Session</u></b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1">Level</b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1">Seconds/trial</b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1" id="default_trials_increase">Events:
				<?php echo $_NBACK['level'] * 5 + 20; ?> + </b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1" id="time_of_session">Seconds</b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1" id="eventLength_lbl">Event length</b>
			</div>
			<div class="n_back_options_values_container">
				<b tabindex="-1" >Color</b>
			</div>
		</div>
	<!-- _Values -->
		<div id="n_back_options_values">
		<form tabindex="-1" id="n_back_options_form" method="POST" action="index.php?index=2" ></form>
	<!-- gameMode-->

		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<div class="down" tabindex="1"  onmousedown="ChangePositionValueInNBackOptions('down', '')" >&#9664;</div>
				<input class="input" tabindex="-1" form="n_back_options_form" name="gameMode" id="_gameMode" type="text"  value="<?php echo $_NBACK['gameMode']?>" readonly onwheel="ChangePositionValueInNBackOptions('-1', event)" autofocus/>
				<div class="up" id="_gameMode_up" tabindex="2"  onmousedown="ChangePositionValueInNBackOptions('up', '')" >&#9654;</div>
			</div>
		</div>
	<!--sessions-->
		<div class="n_back_options_values_container"></div>
	<!--level -->
		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<div class="down" tabindex="3"  onmousedown="ChangeNuberValue('down',level, 0)" >&#8722;</div>
				<input class="input" tabindex="-1" form="n_back_options_form"   name="level" id="level" id="level" type="text" step="1" min="1" max="20" value="<?php echo round($_NBACK['level'], 2)?>" readonly onwheel="ChangeNuberValue('-1',level, 0, event)" <?php if($_NBACK["gameMode"] == "Off") echo "style='color:#aaa'";?> />
				<div class="up" tabindex="4"  onmousedown="ChangeNuberValue('up',level, 0)"  >+</div>
			</div>
		</div>
	<!--seconds -->
		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<div class="down" tabindex="5" onmousedown="ChangeNuberValue('down',seconds, 1)" >&#8722;</div>
				<input class="input" tabindex="-1" form="n_back_options_form"   name="seconds" id="seconds" type="text" step="0.1" min="0.1" max="5.0" value="<?php echo round($_NBACK['seconds'], 2)?>" readonly onwheel="ChangeNuberValue('-1',seconds, 1, event)" />
				<div class="up" tabindex="5" onmousedown="ChangeNuberValue('up',seconds, 1)"  >+</div>
			</div>
		</div>
	<!--trial-->
		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<div class="down" tabindex="6" onmousedown="ChangeNuberValue('down',trial, 0)" >&#8722;</div>
				<input class="input" tabindex="-1" form="n_back_options_form"   name="trial" id="trial" type="text" step="5" min="0" max="60" value="<?php echo $_NBACK['trial'] - ($_NBACK['level'] * 5) - 20;?>" readonly onwheel="ChangeNuberValue('-1',trial, 0, event)"/>
				<div class="up" tabindex="7" onmousedown="ChangeNuberValue('up',trial, 0)" >+</div>
			</div>
		</div>
	<!--n_back_long-->
		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<input tabindex="-1" form="n_back_options_form" tabindex="-1"  name="n_back_long" id="n_back_long" type="text"  value="<?php echo round($_NBACK['trial'] * $_NBACK['seconds'])." s";?>" readonly/>
				<section ></section>
			</div>
		</div>
	<!-- eventLength-->
		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<div class="down" tabindex="8" onmousedown="ChangeNuberValue('down',eventLength, 3)" >&#8722;</div>
				<input class="input" tabindex="-1" form="n_back_options_form"  name="eventLength" id="eventLength" type="text" step="0.005" min="0.005" max="4.955" value="<?php echo ($_NBACK['eventLength']);?>" readonly onwheel="ChangeNuberValue('-1',eventLength, 3, event)"/>
				<div class="up" tabindex="9" onmousedown="ChangeNuberValue('up',eventLength, 3)" >+</div>
			</div>
		</div>
	<!--Color -->
		<div class="n_back_options_values_container">
			<div class="number_input_container">
				<div class="down" tabindex="10" onmousedown="ChangeColorValueInNBackOptions('down',color, 'e')" >&#9664;</div>
				<input class="input" tabindex="-1" form="n_back_options_form"  name="color" id="color" type="text" value="<?php echo ($_NBACK['color']);?>" readonly onwheel="ChangeColorValueInNBackOptions('-1',this, event)"/>
				<div class="up" tabindex="11" onmousedown="ChangeColorValueInNBackOptions('up',color, 'e')" >&#9654;</div>
			</div>
		</div>
		<input form="n_back_options_form" name="case" type="text" value="nbackDatas_modify" readonly hidden>
		</div>
		<!--Controls-->
		<div id="n_back_well_come_navigation_container" >
			<div class="n_back_well_come_controls" >
				<button tabindex="12" type="submit" form="n_back_options_form" name="n_back_submit" value="1" id="n_back_options_submit" title="Save"></button>
			</div>
			<div class="n_back_well_come_controls" >
				<a href="index.php">
					<button tabindex="13" id="n_back_options_back"  title="Back"></button>
				</a>
			</div>
		</div>

		<script>
			//document.getElementById("_gameMode_up").addEventListener("onkeydown" , ChangePositionValueInNBackOptions('up', '') );
		</script>

		<?php
	} break;
	case 4:{
		$is_modify_document_id = isset($_post_datas['docid']) ? $_post_datas['docid'] : 'none';
		$doc_content = '';
		$get_doc_datas = '';

		if($is_modify_document_id != 'none'){
			$get_doc_datas = Sql_query('SELECT * FROM documents WHERE id = "'.$is_modify_document_id.'";')[0];
		}
		$doc_privilege = isset($get_doc_datas['privilege']) ? $get_doc_datas['privilege'] : 0;
		if(isset($get_doc_datas['content'])){

			$doc_content =  Include_special_characters($get_doc_datas['content']);
		}
		?>

		<div id="n_back_well_come_navigation_container" >
			<div class="n_back_well_come_controls" >
				<button form="new_n_back_document"  name="n_back_info_create" value="1" id="n_back_info_create" title="Save"></button>
			</div>
			<br>
			<div class="n_back_well_come_controls" >
				<a onclick="Send_docid_from_list('<?php echo $is_modify_document_id; ?>')">
					<button   name="n_back_info_back" value="1" id="n_back_options_back" title="Step back"></button>
				</a>
			</div>
		</div>

		<form action="index.php?index=3&nb_common=5" id="hidden_form_for_docid" method="POST">
			<input type="text" id="doc_list_docid_input" name="docid" hidden value="">
		</form>

		<form method="POST" id="new_n_back_document" action="index.php?index=3&nb_common=5" name="new_n_back_document">
			<div id="n_back_create_document_title_container">
				<label for="document_title">Title:</label>
				<input type="text" id="document_title" name="document_title" lang="en" placeholder="Document's name"  value="<?php if(isset($get_doc_datas['title'])) echo $get_doc_datas['title'] ;?>"  required title="Please fill out"/>

				<input type="text" id="upload" name="upload" value="1" hidden value="">
			</div>
			<div id="new_n_back_document_content_container">
				<div id="doc_privilege_radio_container">
					<label class="radio_container" style="pointer-events:auto;">
						<input type="radio"  id="" name="doc_privilege" value="0"  <?php if($doc_privilege == 0) echo 'checked';?>>
						<span class="circle" ><span>Transparent
					</label>

					<label class="radio_container" style="pointer-events:auto;">
						<input type="radio"  name="doc_privilege" value="1"  <?php if($doc_privilege == 1) echo 'checked';?>>
						<span class="circle"><span>Only registed people
					</label>
				<?php
				if($_SESSION['user_datas']['privilege'] >= 2){
				echo '
					<label class="radio_container" style="pointer-events:auto;">
						<input type="radio"  name="doc_privilege" value="2"  ',($doc_privilege == 2) ? 'checked' : '','>
						<span class="circle"><span>Internal members
					</label>';
				}
				if($_SESSION['user_datas']['privilege'] == 3){
				echo '
					<label class="radio_container" style="pointer-events:auto;">
						<input type="radio"  name="doc_privilege" value="3" ',($doc_privilege == 3) ? 'checked' : '','>
						<span class="circle"><span>Privilege 3
					</label>';
				}
				?>
				</div>
				<p>Content</p>
				<textarea form="new_n_back_document" spellcheck="false" type="text" name="document_content" id="new_n_back_document_content" autocomplete="off"
				rows="<?php echo ceil((strlen($doc_content) + substr_count($doc_content, ' ')) / 152) + substr_count($doc_content, "\n");//152 character in a line ?>" >
				<?php
				if($is_modify_document_id){

					echo $doc_content;

				}
				?>
				</textarea>
				<input type="text" value="<?php echo $is_modify_document_id; ?>" name="docid" hidden readonly />
				<input type="type" name="case" value="upload_document_for_n_back" hidden readonly />
			</div>
		</form>
		<?php
	}break;
	case 5:{
		if( (isset($_post_datas['docid']) && $_post_datas['docid'] != 'none') || isset($_post_datas['upload'])){

		$doc_id = isset($_post_datas['upload']) ? Upload_document_datas() : $_post_datas['docid'];
		$document_content = Sql_query($sql = 'SELECT d.content AS content, d.userID, d.title, (SELECT name FROM users WHERE id = d.userID) as name, substr(timestamp, 1, 16) as timestamp FROM documents AS d WHERE id = "'.$doc_id.'";')[0];

		if(count($document_content) >0){
		echo '<div id="document_heaher_container">
					<pre>Title:  ',$document_content['title'],'<br>Author: ',$document_content['name'],'<br>Upload: ',$document_content['timestamp'],'</pre>
				</div>';
		echo '<div id="document_content_container">';
				echo Include_special_characters($document_content['content']);
		echo '</div>';
		}
		?>
		<div id="n_back_well_come_navigation_container">
			<?php
			if(isset($_SESSION['user_datas']['id']) && (isset($document_content['userID']) &&
			round($_SESSION['user_datas']['id']) == round($document_content['userID'])|| $_SESSION['user_datas']['privilege'] == 3)){
			?>
			<div class="n_back_well_come_controls">
				<a onclick="Send_docid_from_list('<?php echo $doc_id; ?>')">
					<button tabindex="2" id="n_back_edit_doc" onclick="finction(e){}" title="Edit content"></button>
				</a>
			</div>
			<div class="n_back_well_come_controls">
				<a href="#" onclick="delete_doc();">
					<button tabindex="2" id="n_back_del_doc" title="Delite file">
					</button>
				</a>
			</div>
			<form method="post" action="index.php?index=3&nb_common=6" id="doc_delete_form">
				<input type="text" readonly hidden value="<?php echo $doc_id; ?>" name="docid">
			</form>
			<?php
			}
			?>
			<div class="n_back_well_come_controls">
				<a href="index.php?index=3&nb_common=2">
					<button tabindex="2"  name="n_back_info_back" value="1" id="n_back_options_back" title="Step back"></button>
				</a>
			</div>
		</div>

		<form action="index.php?index=3&nb_common=4" id="hidden_form_for_docid" method="POST">
			<input type="text" id="doc_list_docid_input" name="docid" hidden value="">
		</form>

		<script type="text/javascript">
		function delete_doc(){
			var r = confirm("do you want?");
			if(r == true){
				document.getElementById('doc_delete_form').submit();
			}
		}
		</script>
	<?php
	}
	else{
		header('Location: index.php?index=3&nb_common=2');
	}
	}break;
	case 6:{

		if(isset($_POST['docid'])){
			$doc_id = $_POST['docid'];
			if(Sql_execute_query('DELETE FROM documents WHERE id = "'.$doc_id.'";') == 0){
				echo '<div id="n_back_modify_level"><span color="#0a0" style="font-size: 30px;">Delete successful</span></div>';
			}
			else{
				echo '<div id="n_back_modify_level"><span color="#0a0" style="font-size: 30px;">Delete faild</span></div>';
			}
			header('refresh: 2; url=index.php?index=3&nb_common=2');
		}
	}
}
?>
<script type="text/javascript">
var pages = parseInt("<?php echo isset($get_query_count) ? $get_query_count : -1;?>");
var current_page = parseInt("<?php echo isset($offset) ? $offset : '-1'; ?>");

function  N_back_page_back(){
	if(current_page > 0)
	{
		current_page--;
		console.log("index.php?index=3&nb_common=2&page="+(current_page));
		window.location = "index.php?index=3&nb_common=2&offset="+current_page+"&count=<?php if(isset($get_query_count)) echo $get_query_count ?>";
	}
}
function  N_back_page_forward(){
	if(current_page < pages -1)
	{
		current_page++;
		console.log("index.php?index=3&nb_common=2&page="+(current_page));
		window.location = "index.php?index=3&nb_common=2&offset="+current_page+"&count=<?php if(isset($get_query_count)) echo $get_query_count ?>";
	}
}
function Send_docid_from_list(e){
	document.getElementById("doc_list_docid_input").value = e;
	document.getElementById("hidden_form_for_docid").submit();
}
</script>
<?php
function Upload_document_datas(){

	GLOBAL 	$userID,
			$_post_datas;

	$exit = -1;
	$doc_id = $_post_datas['docid'];
	$title = $_post_datas['document_title'];
	$doc_privilege = $_post_datas['doc_privilege'];

	if(isset($title) && isset($_post_datas['document_content'])){

		$doc_content = Escape_special_characters_except_less_and_grater_then_characters($_post_datas['document_content']);
		switch($doc_id){
			case 'none':{
				$sql_documet = 'INSERT INTO documents(userID,title, content, privilege) VALUES ("'.$userID.'","'.$title.'","'.$doc_content.'","'.$doc_privilege.'")';
			}break;
			case 'delete':{
				$sql_documet = 'DELETE FROM documentse WHERE id = "'.$doc_id.'";';
			}break;
			default:{
				$sql_documet = 'UPDATE documents set title = "'.$title.'", content = "'.$doc_content.'" , privilege = "'.$doc_privilege.'"  where id = "'.$doc_id.'";';
			}
		}
		if(Sql_execute_query($sql_documet) == 0){
			$exit = strval(Sql_query($get_doc_id = 'SELECT id FROM documents WHERE title = "'.$title.'" order by timestamp DESC Limit 1;')[0]['id']);
		}
	}
	return $exit;
}

?>
