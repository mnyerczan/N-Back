<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# JavaScriptel és php-val is meghívható php függvények								  					      //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if(isset($_GET['special_chararcters']) && $_GET['special_chararcters'] == 0){

	echo Escape_special_characters($_GET['msg']);

}
elseif(isset($_GET['special_chararcters']) && $_GET['special_chararcters'] == 1){

	echo Include_special_characters($_GET['msg']);

}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# String formázó függvény. Kiszüri a speckó karaktereket a DB-be valo inzertálás elött.					      //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Escape_special_characters($msg){


	if(substr_count($msg, "'") > 0){
		$msg_t = explode("'", $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_A";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	if(substr_count($msg, '"') > 0){
		$msg_t = explode('"', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_B";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	if(substr_count($msg, '`') > 0){
		$msg_t = explode('`', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_C";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}
	if(substr_count($msg, '<') > 0){
		$msg_t = explode('<', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_D";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}
	if(substr_count($msg, '>') > 0){
		$msg_t = explode('>', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_E";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}


	return $msg;
 }
function Escape_special_characters_except_less_and_grater_then_characters($msg){

		if(substr_count($msg, "'") > 0){
		$msg_t = explode("'", $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_A";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	if(substr_count($msg, '"') > 0){
		$msg_t = explode('"', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_B";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	if(substr_count($msg, '`') > 0){
		$msg_t = explode('`', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."@@@@_C";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	return $msg;
}
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//# String formázó függvény. Beilleszti a speckó karaktereket a DB-ből valo kiolvasás után.					      //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Include_special_characters($msg){

	if(substr_count($msg, "@@@@_A") > 0){
		$msg_t = explode("@@@@_A", $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i]."'";
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	if(substr_count($msg, '@@@@_B') > 0){
		$msg_t = explode('@@@@_B', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i].'"';
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}

	if(substr_count($msg, '@@@@_C') > 0){
		$msg_t = explode('@@@@_C', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i].'`';
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}
		if(substr_count($msg, '@@@@_D') > 0){
		$msg_t = explode('@@@@_D', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i].'< ';
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}
		if(substr_count($msg, '@@@@_E') > 0){
		$msg_t = explode('@@@@_E', $msg);
		$msg='';

		for($i=0;$i<count($msg_t);$i++){

			if($i < count($msg_t)-1){
				$msg .= $msg_t[$i].' >';
			}
			else{
				$msg .= $msg_t[$i];
			}
		}
	}


	return $msg;
 }
?>
