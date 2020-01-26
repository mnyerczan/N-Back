<?php if ( $indicator->infoLabel == 'Off'):?>
<style>
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
</style>
<?php endif ?>
<div id="info_lbl_container" style="position: fixed;top: 100px;right: 0px;width: 80px;">
	<div id="level_print_container"><?= $indicator->gameMode ?></div>
	<br>	
	<span id="n_back_result_true">Correct:</span>
	<p id="info_correct"><?= $indicator->correctHit ?></p>
	<span id="n_back_result_false">Wrong:</span>
	<p id="info_wrong"><?= $indicator->wrongHit ?>	</p>
	<span id="n_back_result_false">Percent: </span>
	<p id="info_percent"><?= $indicator->percent ?></p>
	<span id="n_back_result_time">Time:</span>	
	<p id="info_time"><?= $indicator->sessionLength ?></p>
	<br>
	<button value="hide" id="info_lbl_hide_btn" onclick="Hide_info_lbl();" onmouseover="a();"><?=$indicator->infoLabel?></button>
</div>
