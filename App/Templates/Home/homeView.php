<main class="main-home">
	
<?php if( strlen($home->content) > 0 ): ?>
	Include_special_characters($home->content);
<?php else: ?>
	<div id="empty-wellcome">Position N-Back</div>	
<?php endif ?>


</main>
