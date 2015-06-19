<?php
$activity = $vars['activity'];
$target = "_blank";
if(strpos($activity -> perma_url, parse_url(elgg_get_site_url(), PHP_URL_HOST)) !== FALSE)
	$target = '_self';
?>
<div class="activity-rich-post">
	<a href="<?= $activity -> perma_url ?>" target="<?= $target ?>">
		
		<?php if($activity->thumbnail_src){?>
			<div class="thumbnail-wrapper">
				<img src="<?= $activity->thumbnail_src ?>" class="thumbnail"/>
			</div>
		<?php } ?>
		
		<h3><?= htmlspecialchars($activity->title, ENT_QUOTES, 'UTF-8') ?></h3>
		<p><?= htmlspecialchars($activity->blurb, ENT_QUOTES, 'UTF-8') ?></p>
		
		<p class="url"><?= parse_url($activity -> perma_url, PHP_URL_HOST) ?></p>
	</a>
</div>
