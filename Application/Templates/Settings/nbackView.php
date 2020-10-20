<script src="<?=BACKSTEP?>Public/js/nbackSettings.js?v=<?= RELOAD_INDICATOR ?>"></script>
<form action="<?= APPROOT ?>/settings/nback" method="POST">	
	<div class="nback-setting-contents">
		<div class="nback-settings-describtion">Describtion:

		

		In Position mode, you cannot change the game level value.

		The value of the trials is always the minimum value of the level of the game, multiplied by five, to which twenty must be added. So twenty-five is the smallest option in a run.
		
		Min Trials = 20 + Level * 5

		The length of the event could not be longer than the distance between two events in time.
		
		Seconds between events always larger than Event's length!

		</div>
		<div class="title">Choose your game mode</div>
		<div class="nback-game-mode-name">Game mode</div>
		<select class="nback-game-mode-value" id="nback-game-mode-value" name="gameMode">
			<option value="Manual" 	 <?php if($user->gameMode == "Manual") echo 'Selected'; ?>>Manual</option>
			<option value="Position" <?php if($user->gameMode == "Position") echo 'Selected'; ?>>Position</option>
		</select>
		<div class="nback-level-name">Level</div>
		<input class="nback-level-value" id="nback-level-value" type="number" min="1" max="20" step="1" value="<?= $user->level ?>" <?php if($user->gameMode == 'Position') echo 'readonly'  ?> name="level" />
		<div class="nback-seconds-name">Seconds between events</div>
		<input class="nback-seconds-value" id="nback-seconds-value" type="number" min="1" max="5" step="0.1" value="<?= $user->seconds ?>" name="seconds" />
		<div class="nback-trials-name">Trials</div>
		<input class="nback-trials-value" id="nback-trials-value" type="number" min="<?= $user->level * 5 + 20 ?>" max="1000" step="5" value="<?= $user->trials ?>" name="trials"/>
		<div class="nback-eventlength-name">Event's length</div>
		<input class="nback-eventlength-value" id="nback-eventlength-value" type="number" min="0.1" max="3" step="0.1" value="<?= $user->eventLength ?>" name="eventLength"/>
		<div class="nback-color-name">Color</div>
		<select class="nback-color-value" value="<?= $user->color ?>" name="color">	
			<option value="blue">Blue</option>
			<option value="cyan">Cyan</option>
			<option value="green">Green</option>
			<option value="grey">Grey</option>
			<option value="magenta">Magenta</option>
			<option value="red">Red</option>
			<option value="yellow">Yellow</option>
		</select>
		<div class="nback-form-submit">
			<input class="btn btn-grn sml-btn" type="submit" value="Update">
		</div>	

	</div>
</form>

