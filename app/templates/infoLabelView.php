<?php if ( $indicator->infoLabel == 'Off'):?>
<style>
	#info-container{
		visibility: hidden;
	}
	#info-hide-btn{
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
<div class="info-container">
	<div id="level-print-container"><?= $indicator->gameMode ?></div>
	<h6>Correct:</h6>
	<span ><?= $indicator->correctHit ?></span>
	<h6>Wrong:</h6>
	<span ><?= $indicator->wrongHit ?></span>
	<h6>Percent:</h6>
	<span ><?= $indicator->percent ?></span>
	<h6>Time:	</h6>
	<span ><?= $indicator->sessionLength ?></span>
	<section value="hide" class="btn btn-gldnrd" onclick="Hide-info-lbl();" onmouseover="a();"><?=$indicator->infoLabel?></section>
</div>
