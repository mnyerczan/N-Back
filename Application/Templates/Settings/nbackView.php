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
