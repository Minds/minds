<?php

global $CONFIG;
$dbprefix = $CONFIG->dbprefix;

// Get callbacks
$entity_guid = get_input('entity', false);
$time_method = get_input('time_method', false);
$time_posted = get_input('time_posted', false);

$entity = get_entity($entity_guid);

$options['joins'][] = "JOIN {$dbprefix}objects_entity o ON o.guid = rv.object_guid";
$options['joins'][] = "LEFT JOIN {$dbprefix}annotations a ON a.id = rv.annotation_id";
$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings m ON m.id = a.value_id";

if (elgg_instanceof($entity,'group')) {
	$options['wheres'][] = "((rv.action_type <> 'comment' AND o.description REGEXP '!" . $entity->name . "[[:>:]]') OR (m.string REGEXP '!" . $entity->name . "[[:>:]]'))";
	$mention = '!'.$entity->name;
} else if (elgg_instanceof($entity,'user')) {
	$options['wheres'][] = "((rv.action_type <> 'comment' AND o.description REGEXP '@" . $entity->name . "[[:>:]]') OR (m.string REGEXP '@" . $entity->name . "[[:>:]]'))";
	$mention = '@'.$entity->name;
} else {
	return;
}

$options['types_filter'] = get_input('types_filter');
$options['subtypes_filter'] = get_input('subtypes_filter');

// set time_method and set $where_with_time in case of multiple query
if ($time_method == 'lower') {
	$options['posted_time_lower'] = (int)$time_posted+1; // +1 for not repeat first river item
} elseif ($time_method == 'upper') {
	$options['posted_time_upper'] = (int)$time_posted-1; // -1 for not repeat last river item
}

// Prepare wheres clause for filter
if ($options['subtypes_filter']) {
	$filters = "object' AND (rv.subtype IN ('";
	$filters .= implode("','", $options['subtypes_filter']);
	$options['types_filter'][] = $filters . "'))";
}
if ($options['types_filter']) {
	$filters = "((rv.type = '";
	$filters .= implode("') OR (rv.type = '", $options['types_filter']);
	if (substr($filters, -1) == ')') {
		$filters .= ')) ';
	} else {
		$filters .= "')) ";
	}
	$options['wheres'][] = $filters;
}

$defaults = array(
	'offset' => (int) get_input('offset', 0),
	'limit' => (int) get_input('limit', 30),
	'pagination' => FALSE,
	'count' => FALSE,
);
$options = array_merge($defaults, $options);
$items = elgg_get_river($options);

global $jsonexport;
$jsonexport['results'] = array();

if (!empty($items)) {
	foreach ($items as $item) {
		if (elgg_view_exists($item->view, 'json')) {
			elgg_view($item->view, array('item' => $item, 'mention' => $mention), '', '', 'json');
		} else {
			elgg_view('river/item', array('item' => $item, 'mention' => $mention), '', '', 'json');
		}
	}

	$temp_subjects = array();
	foreach ($jsonexport['results'] as $item) {
		if (!in_array($item->subject_guid, $temp_subjects)) $temp_subjects[] = $item->subject_guid; // store user

		$item->posted_acronym = htmlspecialchars(strftime(elgg_echo('friendlytime:date_format'), $item->posted)); // add date

		$item->menu = deck_return_menu(array(
			'item' => $item,
			'sort_by' => 'priority'
		));

		unset($item->view); // delete view
	}

	$jsonexport['users'] = array();
	foreach ($temp_subjects as $item) {
		$entity = get_entity($item);
		$jsonexport['users'][] = array(
			'guid' => $item,
			'type' => $entity->type,
			'username' => $entity->username,
			'icon' => $entity->getIconURL('small'),
		);
	}

} else if (!$time_method) {

	$jsonexport['results'] = '<table height="100%" width="100%"><tr><td class="helper">'. elgg_echo('deck_river:helper:nothing') . '</td></tr></table>';

}

echo json_encode($jsonexport);
