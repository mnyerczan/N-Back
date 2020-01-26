<div id="start_page_container" >
<?php


//$content = Sql_query('SELECT content FROM documents WHERE title = "start_page" AND privilege = 3;');



if( strlen($home->content) > 0 )
{
	echo Include_special_characters($home->content);
}
else {
	echo '<div id="n_back_modify_level">Position N-Back</div>';
	exit;
}


?>
</div>
<style>
	body{
		overflow-x: hidden;
	}
</style>
<div id="n_back_well_come_navigation_container">
	<div class="n_back_well_come_controls">
		<a href="index.php?index=3&nb_common=1" tabIndex="-1">
			<button tabindex="1" id="n_back_start_button" autofocus title="Start game"></button>
		</a>
	</div>
	<div class="n_back_well_come_controls">
		<a href="index.php?index=3&nb_common=3" tabIndex="-1">
			<button tabindex="2" id="n_back_options_button" title="Options" ></button>
		</a>
	</div>
	<div class="n_back_well_come_controls">
		<a href="index.php?index=3&nb_common=2" tabIndex="-1">
			<button tabindex="3" id="n_back_info_button" title="documents">	</button>
		</a>
	</div>
</div>