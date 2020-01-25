<?php  //	$navbar 		?>
<div class="nav_bar">
	<div class="navbar-hdr">
		<a href="/" title="Main page" >			
			<img id="main_header_img" src="<?=APPLICATION?>img/brain_logo.png" >
			<h1>N-Back</h1>
		</a>
	</div>
	<div class="increase-container">
	</div>		
	<nav class="nav-lnks">
		<?php for($i = 0; $i < count($navbar['menus']); $i++):?>
			<?php if ( $navbar['menus'][$i]->child ): ?>
				<section class="hidden-section" tabindex="<?=$i?>">
					<a href="#">
						<span><?= $navbar['menus'][$i]->name ?></span>					
						<img src="<?=APPLICATION?>img/down_white.png" class="nav-lnks-arrow">				
						<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
							<img src="<?= APPLICATION.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
						<?php endif ?>
					</a>
					<nav class="nav-hidden">
						<?php foreach( $navbar['childMenus'][ $navbar['menus'][$i]->id ]  as $child ): ?>
						<a href="<?=$child->path ?>">
							<span><?= $child->name ?></span>														
							<?php if ($child->ikon != 'none'): ?>
								<img src="<?= APPLICATION.$child->ikon ?>"  class="nav-lnks-ikn">
							<?php endif ?>
						</a>
						<?php endforeach ?>
					</nav>
				</section>
			<?php else: ?>
				<a href="<?=$navbar['menus'][$i]->path?>">
					<span><?= $navbar['menus'][$i]->name ?></span>					
					<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
						<img src="<?= APPLICATION.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
					<?php endif ?>
				</a>
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
		<?php for($i=0; $i< sizeof($navbar['sessions']); $i++):?>
			<?php for($i=0; $i< sizeof($navbar['sessions']); $i++):?>        
				<?php if( $navbar['sessions'][$i]->percent >= 80):?>
					<h6>End at: <?=$navbar['sessions'][$i]->time?>,<br> 
						<span class="good_trial_nav_not_font"><?=$navbar['sessions'][$i]->percent?>%</span>, 
						level: <?= $navbar['sessions'][$i]->level?>, <?= ($navbar['sessions'][$i]->manual == 1) ? 'manual' : 'position'?>
					</h6>
				<?php elseif ($navbar['sessions'][$i]->percent <= 50):?>					
					<h6>End at:<?= $navbar['sessions'][$i]->time?>,<br>
						<span class="bad_trial_nav_not_font"><?=$navbar['sessions'][$i]->percent?>%</span>, 
						level: <?=$navbar['sessions'][$i]->level?>, <?= ($navbar['sessions'][$i]->manual == 1) ? 'manual' : 'position'?>
					</h6>
				<?php else: ?>
					<h6>End at: <?=$navbar['sessions'][$i]->time?>,<br>
						<?=$navbar['sessions'][$i]->percent?>%, 
						level: <?= $navbar['sessions'][$i]->level?>, <?= ($navbar['sessions'][$i]->manual == 1) ? 'manual' : 'position'?>
					</h6>					
				<?php endif?>
			<?php endfor ?>
		<?php endfor ?>
	</section>
</div>