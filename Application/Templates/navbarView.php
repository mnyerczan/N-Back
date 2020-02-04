<?php  //	$navbar 		?>
<aside class="nav-bar">
	<div class="navbar-hdr">
		<img src="<?= APPROOT ?><?=APPLICATION?>img/brain_logo.png" >
		<a href="<?=APPROOT?>" title="Main page" >	N-Back</a>
	</div>
	<div class="increase-container">
	</div>		
	<nav class="nav-lnks">
		<?php for($i = 0; $i < count($navbar['menus']); $i++):?>
			<?php if ( $navbar['menus'][$i]->child && $user->privilege > 0 ): ?>
				<section class="hidden-section" tabindex="<?=$i?>">
					<a href="#" class="hidden-anchor">
						<div class="nav-bar-btn">
							<span><?= $navbar['menus'][$i]->name ?></span>					
							<img src="<?= APPROOT ?><?=APPLICATION?>img/down_white.png" class="nav-lnks-arrow">				
							<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
								<img src="<?= APPROOT ?><?= APPLICATION.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
							<?php endif ?>
						</div>
					</a>
					<nav class="nav-hidden">
						<?php foreach( $navbar['childMenus'][ $navbar['menus'][$i]->id ]  as $child ): ?>
						<a href="<?=$child->path ?>">
							<div class="nav-bar-btn">
								<span><?= $child->name ?></span>														
								<?php if ($child->ikon != 'none'): ?>
									<img src="<?= APPROOT ?><?= APPLICATION.$child->ikon ?>"  class="nav-lnks-ikn">
								<?php endif ?>
							</div>
						</a>
						<?php endforeach ?>
					</nav>
				</section>
			<?php elseif($user->privilege > 0): ?>
				<a href="<?= $navbar['menus'][$i]->path?>">
					<div class="nav-bar-btn">
						<span><?= $navbar['menus'][$i]->name ?></span>					
						<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
							<img src="<?= APPROOT ?><?= APPLICATION.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
						<?php endif ?>
					</div>					
				</a>				
			<?php else: ?>
				<div class="nav-bar-btn">
					<span><?= $navbar['menus'][$i]->name ?></span>					
					<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
						<img src="<?= APPROOT ?><?= APPLICATION.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
					<?php endif ?>
					</div>
			<?php endif ?>
		<?php endfor ?>		
	</nav>

	<section id="navbar-infos">
		<h4 class="navbar-second-text">Last day's games</h4>
		<h4 class="nav_bar_text">The total time of sessions in the last 24 hours:</h4>
		<h5><?=$navbar['times']->last_day?> min</h5>
		<h4 class="navbar-second-text">Today's games</h4>
		<h4 class='nav_bar_text' >Total duration of today's games:</h4>
		<h5>
			<span <?= explode(":",$navbar['times']->today_position)[0] >= 20 ? ' id="goal_label" ' : ""?> >
				<?= $navbar['times']->today?>
			</span> min
		</h5> 		
	</section>
	<section id="navbar-sessions">
		<h4>Last 10 sessions:</h4>
		<?php for($i=0; $i < sizeof($navbar['sessions']) && $navbar['sessions'][$i]->timestamp !== '1970-01-01 00:00:00'; $i++):?>
			<?php for($i=0; $i< sizeof($navbar['sessions']); $i++):?>        
				<?php if( $navbar['sessions'][$i]->percent >= 80):?>
					<h6>End at: <?=$navbar['sessions'][$i]->endAt?>,<br> 
						<span class="good-trial"><?=$navbar['sessions'][$i]->percent?>%</span>, 
						level: <?= $navbar['sessions'][$i]->level?>, <?=$navbar['sessions'][$i]->gameMode?>
					</h6>
				<?php elseif ($navbar['sessions'][$i]->percent <= 50):?>					
					<h6>End at: <?= $navbar['sessions'][$i]->endAt?>,<br>
						<span class="bad-trial"><?=$navbar['sessions'][$i]->percent?>%</span>, 
						level: <?=$navbar['sessions'][$i]->level?>, <?=$navbar['sessions'][$i]->gameMode?>
					</h6>
				<?php else: ?>
					<h6>End at: <?=$navbar['sessions'][$i]->endAt?>,<br>
						<?=$navbar['sessions'][$i]->percent?>%, 
						level: <?= $navbar['sessions'][$i]->level?>, <?=$navbar['sessions'][$i]->gameMode?>
					</h6>					
				<?php endif?>
			<?php endfor ?>
		<?php endfor ?>
	</section>
</aside>