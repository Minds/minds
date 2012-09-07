<?php
if ($vars['events']) {
	foreach ($vars['events'] as $entity) {
		echo elgg_view_entity($entity['event']);
	}
	//echo elgg_view_entity_list($vars['events'], $vars['count'], $vars['offset'], $vars['limit'], false, false);
}
