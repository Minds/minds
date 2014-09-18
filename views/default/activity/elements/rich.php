<?php
$activity = $vars['activity'];
?>
<div class="activity-rich-post">
	<a href="<?= $activity -> perma_url ?>" target="_blank">
		<h3><?= $activity -> title ?></h3>
		<p><?= $activity -> blurb ?></p>
		
		<p class="url"><?= $activity -> perma_url ?></p>
	</a>
</div>