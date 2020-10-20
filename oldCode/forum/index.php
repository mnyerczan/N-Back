<script type="text/javascript" src="scripts/libAjax.js" /></script>

<?php

#rp = Remote user Privilege
if(isset($_SESSION['user_datas']['id']) && isset($_POST['rp']) && $privilege >= $_POST['rp'])
{
	$rp = $_POST['rp'];
	#rid = room id
	if(isset($_POST['rid'])){$rid = $_POST['rid'];}

	# r = room name.
	if(isset($_POST['r'])){$getr = Include_special_characters($_POST['r']);}
	# offset = a lekérdezés limit eltolása
	if(isset($_POST['offset'])){$offset = $_POST['offset'];}
	else{$offset = 0;}

	$limit = isset($_cookie_datas['flimit']) ? $_cookie_datas['flimit'] : 4;
	?>
	<div id="chat_container">
		<script>
		<?php
			echo '
			var offset 	= 	"',$offset,'",
				limit		= 	"'.$limit.'",
				theme		= 	"'.$_SESSION['user_datas']['theme'].'",
			user_priv	=	"'.$_SESSION['user_datas']['privilege'].'",
			privilege	= 	"'.$privilege.'",
			limit			=	"'.$limit.'",
			get_logs		= 	1,
			main_theme	=	"'.$main_theme.'",
			rid			=	"'.$rid.'",
			rp				=  "'.$rp.'",
			uid			=	"'.$_SESSION['user_datas']['id'].'";'
		?>

		var rows_count = -1;
		var msg;
		$.ajaxSetup({chache:false});



		function submit_chat(){

			/*
			* Log feltöltéséért felelős script.
			*
			*/


			if(document.getElementById("chat_message").value == ""){
				return;
			}

			let xmlhttp = new XMLHttpRequest();

			let 	msg 	= document.getElementById("chat_message").value.trim(),
					title = document.getElementById("msg_title_input").value;

			xmlhttp.onreadystatechange = function(){

				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){

					modal.style.display = "none";
					document.getElementById('forum_logs').innerHTML = xmlhttp.responseText;
					document.getElementById("chat_message").value = "";
					document.getElementById("msg_title_input").value = "";
				}
				else
					console.log("readyState: "+xmlhttp.readyState+", status: "+xmlhttp.status);
			}

			let params = new FormData();

			params.append("uname", "<?php echo $_SESSION['user_datas']['name'];?>");
			params.append("msg", msg);
			params.append("menuID", "<?php echo $rid;?>");
			params.append("uid", "<?php echo $_SESSION['user_datas']['id'];?>");
			params.append("title", title);
			params.append("limit", limit);
			params.append("offset", offset);
			params.append("privilege", <?php echo $rp; ?> );
			params.append("user_priv", <?php echo $_SESSION['user_datas']['privilege'] ?>);
			params.append("main_theme", "<?php echo $main_theme; ?>");

			xmlhttp.open("POST", 'forum/insert.php', true);
			xmlhttp.send(params);

			//visszaadja az xml értékét
			xmlhttp.onload = function () {
				console.log(this.responseXML);
				console.log(xmlhttp.statusText);
			};
			document.getElementById('navigator_container').style.visibility = 'visible';
		}

		$(document).ready(function(e){

			/*
			* Tábla checkkelésért és változás utáni betöltéséért felelős eljárás.
			*
			*/


			setInterval(function(e){

				$.ajax({

					type: "text",
					url : "forum/logs.php?count="+rows_count+"&rid=<?php echo $rid ?>",
					async: false,
					success:function(text){
						//a trim() függvény levágja a string körül lévő space karaktereket
						num_of_logs_in_db = text.trim();
					}
				});
				document.getElementById("num_of_logs").innerHTML = "#"+num_of_logs_in_db;

				if(parseInt(num_of_logs_in_db) <= limit){
					document.getElementById("left_arrow_container").style.visibility = "hidden";
					document.getElementById("right_arrow_container").style.visibility = "hidden";
				}
				else{
					document.getElementById("left_arrow_container").style.visibility = "visible";
					document.getElementById("right_arrow_container").style.visibility =  "visible";
				}


				if(num_of_logs_in_db != rows_count){

					rows_count = num_of_logs_in_db;
					$.ajax({

						type: "text",
						url : "forum/logs.php?uid="+ uid +"&rid="+ rid +"&main_theme="+ main_theme +"&get_logs=1&offset="+ offset +"&limit="+ limit +"&privilege="+ rp +"&user_priv="+ user_priv,
						async: false,
						success:function(text){
							msg = text.trim();
						}
					});
					if(msg==""){
						document.getElementById("forum_logs").innerHTML = "Downloading logs get error!";
// 						window.location = "index.php";
					}
					document.getElementById("forum_logs").innerHTML = msg;
				}

			},	3000);

		});
	</script>
	<div id="forum_header">
		<div id="num_of_logs"><?php $sql = 'SELECT count(*) count FROM logs l, users u where u.id = l.userID and  menuID='.$rid.';'; $rows_count = Sql_query($sql); echo '#',$rows_count['0']['count'];?></div>
		<div id="num_of_items_container" style="float: left;">
			Num of notes:
			<input type="text" name="num_of_items" id="num_of_items" placeholder="<?php echo $limit; ?>" style="width: 30px;"
				onkeydown="Was_enter_down_f(event, rows_count)" />
			<div style="margin: 2px 0;width: 200px; float: right;" id="alert_div"></div>
		</div>
		<div id="num_of_download_logs">
		<?php
			# $pages= megadja az elérhető oldalak számát.
			$pages = ceil($rows_count['0']['count'] / $limit);
			$row_breaker = 1;
			if($pages != 0 )echo 'Pages: ';
			for($i=0;$i<$pages;$i++){
				if($i<$pages -1){
					if($i  == 0){
						# első oldal
						echo '<span id="page_font_'.$i.'" class="highlighted_page_number" onclick="Change_page(',"'",$i,"'",',',')">',$i+1,'</span>,';
					}
					else
						# többi oldal
						echo '<span id="page_font_'.$i.'" onclick="Change_page(',"'",$i,"'",',',')">',$i+1,'</span>,';
				}
				else{
					# utolsó oldal
					echo '<span id="page_font_'.$i.'" onclick="Change_page(',"'",$i,"'",',',')">',$i+1, '</span>';
				}
				if($i > $row_breaker * 14){
					echo '<br><pre></pre>';
					$row_breaker += 1;
				}
			};
		?>
		</div>
	</div>
	<div class="clear"></div>

	<form name="form1" id="form1" style="overflow: auto; pointer-events: auto;">
		<br />
		<div id="forum_container" onload="logsScroll();">
			<div id="forum_logs">
				<div id="forum_load_img_container">
					<img id="forum_start_img" alt="Nice animated gif" src='data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw=='/>
				</div>
			</div>
		</div>
	</form>
	<style>
		/* Add Animation w3schools.com*/
		@-webkit-keyframes slideIn {
			from {bottom: -300px; opacity: 0}
			to {bottom: 0; opacity: 1}
		}
		@keyframes slideIn {
			from {bottom: -300px; opacity: 0}
			to {bottom: 0; opacity: 1}
		}
		@-webkit-keyframes fadeIn {
			from {opacity: 0}
			to {opacity: 1}
		}
		@keyframes fadeIn {
			from {opacity: 0}
			to {opacity: 1}
		}
	</style>
	<!-- Steps/Open The Modal -->
	<div id="navigator_container">
		<div id="navigator_inside_container">
			<div id="left_arrow_container" style="float: left;">
			<img src="img/left_arrow_blue.png" id="left_arrow" class="n_back_info_navigation_img" style="float: left;" onclick="Forum_step_back();"/>
			</div>
			<div style="height:50px;  margin-left: 50px; float: left; background-color: transparent;">
				<div class="wrap">
					<a href="#" id="myBtn" >Add log</a>
				</div>
			</div>
			<div id="right_arrow_container" style="float: right;">
			<img src="img/right_arrow_blue.png" id="right_arrow" class="n_back_info_navigation_img"  onclick="Forum_step_forward();"/>
			</div>
		</div >
	</div>
	<div id="myModal" class="modal" >
		<!--Pop Up message window-->
		<div class="modal-content">
			<div style="width: 30px; float: right;">
				<span class="close">&times;<span>
			</div>
			<div style="width: 94%;">
				<div style="width: auto; float: left">
					<span id="msg_user_name"><?php echo $_SESSION['user_datas']['name'];?></span>
				</div>
			</div>
			<div id="forum_send_msg_container">
				<div id="msg_container">
					<!--<label for="msg_title_input" id="msg_title_label" >Title: </label>-->
					<input type="text" name="title" id="msg_title_input" height="10" placeholder="title..." autocomplete="off">
					<textarea form="form1" name="msg" id="chat_message"  placeholder="Content..." rows="3" autocomplete="off"></textarea>
				</div>
				<div class="wrap" style="float:right;">
					<a href="#" onclick="submit_chat()" id="chat_send">Send</a>
				</div>
			</div>
		</div>
	</div>
	<script>
	var modal 		= document.getElementById('myModal'),
		 btn 			= document.getElementById("myBtn"),
		 span 		= document.getElementsByClassName("close")[0],
		 chat_send 	= document.getElementById("chat_send");

	chat_send.onkeydown= function(){
		document.getElementById("navigator_container").style.visibility = "hidden";
		console.log('0');
	}
	btn.onclick = function() {
		modal.style.display = "block";
		document.getElementById("navigator_container").style.visibility = "hidden";
	}
	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal.style.display = "none";
		document.getElementById("navigator_container").style.visibility = "visible";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
			document.getElementById("navigator_container").style.visibility = "visible";
		}
	}
	</script>
	<form method="POST" action="" id="num_of_logs_form" 										hidden>
		<input type="text" name="rid" 		value="<?php echo $rid;?>" 					hidden>
		<input type="text" name="rp" 			value="<?php echo $rp;?>" 						hidden>
		<input type="text" name="r" 			value="<?php echo $_POST['r'];?>" 			hidden>
		<input type="text" name="offset"  	value="<?php echo $offset;?>" id="offset" hidden>
	</form>
</div>
<?php
}
else{
 	header('Location: index.php');
}
