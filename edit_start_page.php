<!-- Edit starrt page Start -->
<?php

$is_start_page = true;
$get_doc_datas = Sql_query($sql_get_start_page = 'SELECT * FROM documents WHERE title = "start_page";');

if(count($get_doc_datas) == 0){

	Sql_execute_query('INSERT INTO `documents`(user_id, title, content, privilege) VALUES ("00000002","start_page","","3") ;');
	$is_start_page = false;
}
$case = $_GET['choose'];

switch($case){

	case 1:{
		?>
		<div id="n_back_well_come_navigation_container" >
			<div class="n_back_well_come_controls" >
				<div class="tooltip" >
					<button tabindex="2" form="start_page_form"  name="n_back_info_create" value="1" id="n_back_info_create">
						<span class="tooltiptext">Save<span>
					</button>
				</div>
			</div>
			<br>
			<div class="n_back_well_come_controls" >
				<div class="tooltip" >
					<a href="index.php">
						<button tabindex="2"  name="n_back_info_back" value="1" id="n_back_options_back">
							<span class="tooltiptext">Back<span>
						</button>
					</a>
				</div>
			</div>
		</div>
		<form method="POST" id="start_page_form" action="index.php?index=11&choose=2" name="start_page_form">
			<div id="n_back_create_document_title_container">
				<label for="document_title">Title:</label>
				<input type="text" id="document_title" name="document_title" placeholder="Document's name"  value="<?php if(isset($get_doc_datas[0]['title'])) echo $get_doc_datas[0]['title'] ;?>"  readonly />
			</div>
			<div id="new_n_back_document_content_container">
			<p>Content</p>
				<textarea form="start_page_form" type="text" name="document_content" id="new_n_back_document_content" autocomplete="off"
				rows="<?php echo ceil(strlen($get_doc_datas[0]['content']) / 50); ?>" >
		<?php
		if(count($get_doc_datas) > 0){
			echo Include_special_characters($get_doc_datas[0]['content']);
		}
		?>
				</textarea>
			</div>
		</form>
		<?php
	}break;

	case 2:{
		if(isset($_SESSION) && $get_doc_datas[0]['privilege'] == $_SESSION['user_datas']['privilege'] || !$is_start_page){
			Sql_execute_query($sql_update_start_page = 'UPDATE documents SET content = "'.Escape_special_characters_except_less_and_grater_then_characters($_POST['document_content']).'" WHERE title = "start_page";');
		}
   		header('Location: index.php');
	}
}
?>
<!-- Edit starrt page End -->
