<link rel="stylesheet" href="<?=BACKSTEP ?>Public/Style/Main/navbar-structure.css?t=<?=RELOAD_INDICATOR?>">
<table class="navbar-hdr">
	<tr>
		<td><img src="<?= BACKSTEP ?><?=APPLICATION?><?=$navbar->logoImg?>" ></td>
		<td><a href="<?=APPROOT.'/'?>" title="Main page" >N-Back</a></td>
	</tr>
</table>					
<div class="increase-container">
</div>		
<nav class="nav-lnks">
	<?php for($i = 0; $i < count($navbar->menus); $i++):?>
		<?php // Gyerek objektummal rendelkező menü kirajzolása ?>
		<?php if ( $navbar->menus[$i]->child > 0): ?>					
			<?php // Ha a user privilégiuma nagyobb, vagy egyenlő a menűpont privilégiumával, jelenítse meg. ?>				
			<?php if($user->privilege >= $navbar->menus[$i]->privilege): ?>
				<?php // A leugró menü kirajzolása child objektumokkal ?>
				<section tabindex="<?=$i?>">				
					<a href="<?=$navbar->menus[$i]->path?>" class="hidden-anchor">
						<div class="navbar-btn">
							<span><?= $navbar->menus[$i]->name ?></span>														
							<?php if ($navbar->menus[$i]->ikon): ?>
								<img src="<?=$navbar->menus[$i]->ikon?>"  class="nav-lnks-ikn">
							<?php endif ?>
						</div>
					</a>
					<nav >
					<?php if(is_array($navbar->childMenus)): ?>
						<?php foreach( $navbar->childMenus[ $navbar->menus[$i]->id ]  as $child ): ?>
						<a href="<?=$child->path ?>">
							<div class="navbar-btn">
								&#9656; <span><?= $child->name ?></span>														
								<?php if ($child->ikon): ?>
									<img src="<?=$child->ikon?>"  class="nav-lnks-ikn">
								<?php endif ?>
							</div>
						</a>
						<?php endforeach ?>
					<?php endif ?>
					</nav>
				</section>
				<?php // A leugró ablakhoz tartozó szülő objektum kirajzolása ?>			
			<?php endif ?>
		<?php // Gyerek objektummal nem rendelkező menü kirajzolása ?>
		<?php else: ?>
			<a href="<?= $navbar->menus[$i]->path?>">
				<div class="navbar-btn">
					<span><?= $navbar->menus[$i]->name ?></span>					
					<?php if ($navbar->menus[$i]->ikon): ?>
						<img src="<?=$navbar->menus[$i]->ikon?>"  class="nav-lnks-ikn">
					<?php endif ?>
				</div>					
			</a>	
		<?php endif ?>
	<?php endfor ?>		
</nav>

<section id="navbar-infos">
	<h4 class="navbar-second-text">Last day's games</h4>
	<p class="nav_bar_text">The total time of sessions in the last 24 hours:</p>
	<h5><?=$navbar->times->last_day?> min</h5>
	<p class="navbar-second-text">Today's games</p>
	<p class='nav_bar_text' >Total duration of today's games:</p>
	<h5>
		<span <?= explode(":",$navbar->times->today_position)[0] >= 20 ? ' id="goal_label" ' : ""?> >
			<?= $navbar->times->today?>
		</span> min
	</h5> 		
</section>
<section id="navbar-sessions">
	<p>Last 10 sessions:</p>
	<?php for($i=0; $i < sizeof($navbar->sessions) && $navbar->sessions[$i]->timestamp !== '1970-01-01 00:00:00'; $i++):?>
		<?php for($i=0; $i< sizeof($navbar->sessions); $i++):?>        
			<?php if( $navbar->sessions[$i]->percent >= 80):?>
				<h6>End at: <?=$navbar->sessions[$i]->endAt?>,<br> 
					<span class="good-trial"><?=$navbar->sessions[$i]->percent?>%</span>, 
					level: <?= $navbar->sessions[$i]->level?>, <?=$navbar->sessions[$i]->gameMode?>
				</h6>
			<?php elseif ($navbar->sessions[$i]->percent <= 50):?>					
				<h6>End at: <?= $navbar->sessions[$i]->endAt?>,<br>
					<span class="bad-trial"><?=$navbar->sessions[$i]->percent?>%</span>, 
					level: <?=$navbar->sessions[$i]->level?>, <?=$navbar->sessions[$i]->gameMode?>
				</h6>
			<?php else: ?>
				<h6>End at: <?=$navbar->sessions[$i]->endAt?>,<br>
					<?=$navbar->sessions[$i]->percent?>%, 
					level: <?= $navbar->sessions[$i]->level?>, <?=$navbar->sessions[$i]->gameMode?>
				</h6>					
			<?php endif?>
		<?php endfor ?>
	<?php endfor ?>
</section>
