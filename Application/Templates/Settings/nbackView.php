<form action="<?= APPROOT ?>/settings/nback" method="POST">	
	<div class="nback-setting-contents">
		<div class="title">Choose your game mode</div>
		<div class="nback-game-mode-value">Game mode</div>
		<select class="nback-game-mode-control">
			<option value="Manual" 	 <?php if($user->gameMode == "Manual") echo 'Selected'; ?>>Manual</option>
			<option value="Position" <?php if($user->gameMode == "Position") echo 'Selected'; ?>>Position</option>
		</select>
		<div class="nback-level-value">Level</div>
		<input class="nback-level-control" type="number" min="1" max="20" step="1" value="<?= $user->level ?>">
		<div class="nback-seconds-value">Seconds between events</div>
		<input class="nback-seconds-control" type="number" min="1" max="5" step="0.1" value="<?= $user->seconds ?>">
		<div class="nback-trials-value">Trials</div>
		<input class="nback-trials-control" type="number" min="<?= $user->level * 5 + 20 ?>" max="1000" step="5" value="<?= $user->trials ?>" />
		<div class="nback-eventlength-value">Event's length</div>
		<input class="nback-eventlength-control" type="number" min="0.1" max="3" step="0.1" value="<?= $user->eventLength ?>" />
		<div class="nvack-color-value">Color</div>
		<select class="nvack-color-control" value="<?= $user->color ?>">	
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

