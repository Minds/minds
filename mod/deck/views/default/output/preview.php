<?php

$title = elgg_extract('title', $vars);
$description = elgg_extract('description', $vars);
$icon = elgg_extract('icon', $vars);
$url = elgg_extract('url', $vars);
$split_url = explode('/',$url);
?>

<div class="minds-preview">
	<a href="//<?= $url ?>" target="_blank">
		<img src="<?= $icon ?>" class="minds-preview-icon"/>
		<h3><?= $title ?></h3>
		<p><?= $description ?></p>
		<span class="minds-preview-url"><?= $split_url[0] ?></span>
	</a>
</div>
