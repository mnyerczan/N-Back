<?php
if(isset($_cookie_datas['info_label']) && $_cookie_datas["infoLabel"] == 0){

echo
"<style>
	#info_lbl_container{
		visibility: hidden;
	}
	#info_lbl_hide_btn{
		visibility: visible;
		writing-mode: vertical-lr;
		width: 30%;
		height: 70px;
		position: relative;
		left: 50px;
		bottom: 25px;
		color: #009;
	}
</style>";

}?>

<div id="info_lbl_container" style="position: fixed;top: 100px;right: 0px;width: 80px;">

<?php
	#Ha van felhasználó, az db adatait töltse be!
	if(isset($_SESSION["user_datas"]["id"])){
		$info_array = Sql_query($sql = "SELECT * FROM n_back_sessions WHERE user_id = ".$_SESSION["user_datas"]["id"]." ORDER BY timestamp DESC LIMIT 1");
		if(count($info_array) > 0) $info_array = $info_array[0];
	}



?>
	<div id="level_print_container">
		<?php
		echo isset($_SESSION['n_back_datas']['manual']) ?  ($_SESSION['n_back_datas']['manual'] == 'Off' ? $_SESSION['n_back_datas']['level'].'- Back' : 'Manual') :
		(isset($_cookie_datas['manual']) ? ($_cookie_datas['manual'] == 'Off' ? $_cookie_datas['level'].' Back' : 'Manual' ) : '1 Back');
		?>
	</div>
	<hr>
	<span id="n_back_result_true">Correct:</span>
	<p id="info_correct">
		<?php echo isset($info_array) ? (count($info_array) > 0 ? $info_array["correct_hit"] : "--") : (isset($_cookie_datas['correct_hit']) ? $_cookie_datas['correct_hit'] : '--'); ?>
	</p>
	<hr>
	<span id="n_back_result_false">Wrong:</span>
	<p id="info_wrong">
		<?php echo isset($info_array) ? (count($info_array) > 0 ? $info_array["wrong_hit"] : "--") : (isset($_cookie_datas['wrong_hit']) ? $_cookie_datas['wrong_hit'] : '--'); ?>
	</p>
	<hr>
	<span id="n_back_result_false">Percent: </span>
	<br>
	<p id="info_percent">
	<?php
		if(isset($info_array)){
			if(count($info_array) > 0 && ($info_array['correct_hit'] + $info_array['wrong_hit']) > 0){

				echo  (100 -round(100 * ($info_array['wrong_hit'] / ($info_array['correct_hit'] + $info_array['wrong_hit']) ), 1)).'%';
			}
			elseif(count($info_array) > 0){
				echo "100%";
			}
			else echo '--';

		}
		else{
			#Átdolgozásra vár...
			if(isset($_cookie_datas['correct_hit']) && ($_cookie_datas['correct_hit'] + $_cookie_datas['wrong_hit']) > 0){
				if( isset($_cookie_datas['wrong_hit']) && isset($_cookie_datas['correct_hit']))
				echo  (100 -round(100 * ($_cookie_datas['wrong_hit'] / ($_cookie_datas['correct_hit'] + $_cookie_datas['wrong_hit']) ), 1)).'%';
			}
			else if(!isset($_cookie_datas['correct_hit']) ||  !isset($_cookie_datas['correct_hit']) == 0 )
				echo '--';
			else echo '100%';
		}
	?>
	</p>
	<hr>
	<span id="n_back_result_time">Time:</span>
	<br>
	<p id="info_time">
		<?php
			if(isset($info_array) && count($info_array) > 0){
				echo (isset($info_array['time_length']) ?  (floor($info_array['time_length']  / 1000 / 60).':'.
				(strlen($info_array['time_length']  / 1000 % 60) == 1 ? '0'.round($info_array['time_length']  / 1000 % 60)
				: round($info_array['time_length']  / 1000 % 60) ))." m" : "--");
			}
			elseif(isset($info_array)){
				echo "--";
			}
			else{
				echo (isset($_cookie_datas['time_length']) ?  (floor($_cookie_datas['time_length']  / 1000 / 60).':'.
				(strlen($_cookie_datas['time_length']  / 1000 % 60) == 1 ? '0'.round($_cookie_datas['time_length']  / 1000 % 60)
				: round($_cookie_datas['time_length']  / 1000 % 60) ))." m" : "--");
			}
		?>
		</p>
	<hr>
	<!--<span id="n_back_result_false">Event length:</span>
	<p id="info_length">
		<?php echo isset($_SESSION['n_back_datas']['event_length']) ? $_SESSION['n_back_datas']['event_length']." s" :
		(isset($_cookie_datas['event_length']) ? $_cookie_datas['event_length'] : '0.5')." s"; ?>
	</p>-->
	<hr>
	<button value="hide" id="info_lbl_hide_btn" onclick="Hide_info_lbl();" onmouseover="a();"><?php echo isset($_cookie_datas["infoLabel"]) && $_cookie_datas["infoLabel"] == 0 ? "Info" : "Hide"; ?></button>
</div>

