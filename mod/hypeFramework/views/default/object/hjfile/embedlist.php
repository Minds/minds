<?php
	$file = $vars['entity'];
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	$info = "<p class='entity-title'> <a href=\"{$file->getURL()}\">{$file->title}</a></p>";
	$info .= "<p class='elgg-subtitle'>{$friendlytime}";
	$icon = "<a href=\"{$file->getURL()}\">" . elgg_view_entity_icon($file, 'medium') . "</a>";
        ?>
<div id="elgg-object-<?php echo $file->guid; ?>">
	<?php echo elgg_view_image_block($icon, $info); ?>
</div>
