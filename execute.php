<?php

$exit = -1;

if(isset($case)){

	switch($case)
	{
		case 'n_back_datas_modify'			: $exit = Update_nback_datas() == 0 ? '4' : 'error'; break;

		case 'create_user'					: if(Create_user_group() == 0) {$exit = 9; $e = '1';} else $exit = 5; break;

		case 'nb' 							: $exit =  Insert_nb_sessions() == 0 ? '4' : 'error' ; break;

		case 'user_del'						: Delete_user() == 0 ? $e = '2': $e = 'Deleted failed'; $exit = 9; break;
	}
 }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 	A kapcsolat lezárása, továbblépés																   		    //
// 	Nullát nem használunk, összekeveri a stringekkel.														    //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($case != ''){

	switch((int)$exit){

		case 4:header('Location: index.php?');																				break;
		case 5:header('Location: index.php?index=6&error='.$e);																break;
		case 6:header('Location: index.php?index=6&uid='.$user_id);															break;
		case 9:header('Location: index.php?index=9&rep_common='.$e);														break;
		case 10:header('Location: index.php?index=6&uid='.$user_id);														break;
		case 11:header('Location: index.php?'.substr(Get_get_datas_to_sting() , 44)); 										break;
		default : if(isset($error_level) && $error_level > 0) echo  '<div id="bug_container"></div><style>body{overflow: hidden;}</style>';
				else echo "<div src='' id='load'></div><div class='modal_load'></div><style>body{overflow: hidden;}</style>";
	}
}

?>
