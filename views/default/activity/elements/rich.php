<?php
$activity = $vars['activity'];
$target = "_blank";
if(strpos($activity -> perma_url, parse_url(elgg_get_site_url(), PHP_URL_HOST)) !== FALSE)
	$target = '_self';
?>
<div class="activity-rich-post" style="width:100%; margin-left:-2.5%">
	<a href="<?=  htmlspecialchars($activity->perma_url) ?>" target="<?= $target ?>">
		
		<?php if($activity->thumbnail_src){?>
			<div class="thumbnail-wrapper">
				<img src="<?= htmlspecialchars($activity->thumbnail_src) ?>" class="thumbnail"/>
			</div>
		<?php } ?>
		
		<h3><?= strip_tags($activity->title) ?></h3>
		<p><?= strip_tags($activity->blurb) ?></p>
		
		<p class="url"><?= parse_url($activity -> perma_url, PHP_URL_HOST) ?></p>
	</a>
</div>
