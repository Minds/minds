<?php

if (elgg_is_xhr()) {
	$last = get_entity(get_input('guid'));
	$priority = $last->priority;

	$options = get_input('options');
	$pagination_options = get_input('pagination');

	$db_prefix = elgg_get_config('dbprefix');
	$defaults = array(
		'offset' => (int) max(get_input('offset', 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'class' => 'hj-syncable-list',
		'joins' => array("JOIN {$db_prefix}metadata as mt on e.guid = mt.entity_guid
                      JOIN {$db_prefix}metastrings as msn on mt.name_id = msn.id
                      JOIN {$db_prefix}metastrings as msv on mt.value_id = msv.id"
		),
		'wheres' => array("((msn.string = 'priority') AND (msv.string > $priority))"),
		'order_by' => "CAST(msv.string AS SIGNED) ASC"
	);

	$options = array_merge($defaults, $options);

	$items = elgg_get_entities($options);

	if (is_array($items) && count($items) > 0) {
		foreach ($items as $key => $item) {
			$id = "elgg-{$item->getType()}-{$item->guid}";
			$time = $item->time_created;

			$html = "<li id=\"$id\" class=\"elgg-item\" data-timestamp=\"$time\">";
			$html .= elgg_view_list_item($item, $vars);
			$html .= '</li>';

			$output[] = $html;
		}
	}
	print(json_encode($output));
	exit;
}

forward(REFERER);