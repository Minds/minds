<?php
$user = elgg_extract('user', $vars);

// get a list of the users thumbed up content

$entities = \minds\plugin\thumbs\helpers\lists::getUserThumbs($user);

?>

<ul class="thumbs-list elgg-list">
	<?php foreach($entities as $entity){ ?>
		<li class="elgg-item">	<?=	elgg_view_entity($entity); ?></li>
	<?php }	?>
</ul>

	