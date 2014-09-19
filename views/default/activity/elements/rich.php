<?php
$activity = $vars['activity'];
?>
<div class="activity-rich-post">
	<a href="<?= $activity -> perma_url ?>" target="_blank">
		
		<?php if($activity->thumbnail_src){?>
			<div class="thumbnail-wrapper">
				<img src="<?= $activity->thumbnail_src ?>" class="thumbnail"/>
			</div>
		<?php } ?>
		
		<h3><?= $activity -> title ?></h3>
		<p><?= $activity -> blurb ?></p>
		
		<p class="url"><?= parse_url($activity -> perma_url, PHP_URL_HOST) ?></p>
	</a>
</div>