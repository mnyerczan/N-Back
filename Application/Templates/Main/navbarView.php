<?php  //	$navbar 		?>
<div class="nav_bar">
	<div class="navbar-hdr">
		<a href="/" title="Main page" >			
			<img id="main_header_img" src="<?=APPROOT?>img/brain_logo.png" >
			<h1>N-Back</h1>
		</a>
	</div>
	<div class="increase-container">
	</div>		
	<nav class="nav-lnks">
		<?php for($i = 0; $i < count($navbar['menus']); $i++):?>
			<?php if ( $navbar['menus'][$i]->child ): ?>
				<section class="hidden-section">
					<a href="#">
						<span><?= $navbar['menus'][$i]->name ?></span>					
						<img src="<?=APPROOT?>img/down_white.png" style="width: 8px;" class="nav-lnks-arrow">				
						<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
							<img src="<?= APPROOT.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
						<?php endif ?>
					</a>
					<nav class="nav-hidden">
						<?php foreach( $navbar['childLinks'][ $navbar['menus'][$i]->id ]  as $child ): ?>
						<a href="<?=$child->path ?>">
							<span><?= $child->name ?></span>														
							<?php if ($child->ikon != 'none'): ?>
								<img src="<?= APPROOT.$child->ikon ?>"  class="nav-lnks-ikn">
							<?php endif ?>
						</a>
						<?php endforeach ?>
					</nav>
				</section>
			<?php else: ?>
				<a href="<?=$navbar['menus'][$i]->path?>">
					<span><?= $navbar['menus'][$i]->name ?></span>					
					<?php if ($navbar['menus'][$i]->ikon != 'none'): ?>
						<img src="<?= APPROOT.$navbar['menus'][$i]->ikon ?>"  class="nav-lnks-ikn">
					<?php endif ?>
				</a>
			<?php endif ?>
		<?php endfor ?>		
	</nav>
</div>