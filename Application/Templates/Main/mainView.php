<main class="main-home">
	
<?php if( strlen($home->content) > 0 ): ?>
	Include_special_characters($home->content);
<?php else: ?>
	<div id="empty-wellcome">Position N-Back</div>	
<?php endif ?>


</main>

<nav class="inspector-container">	
	<a href="<?= APPROOT ?>nBack" tabIndex="-1">			
		<div class="inspector-btn" id="play-inspector" title="Start game"></div>
	</a>		
	<a href="<?= APPROOT ?>settings" tabIndex="-1">	
		<div class="inspector-btn" id="options-inspector" title="Options"></div>	
	</a>	
	<a href="<?= APPROOT ?>documents" tabIndex="-1">
		<div class="inspector-btn" id="document-inspector" title="documents"></div>
	</a>	
</nav>