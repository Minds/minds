<?php

global $CONFIG, $jsonexport;
$dbprefix = $CONFIG->dbprefix;

// Get callbacks
$tab = get_input('tab', 'default');
$column = get_input('column', false);
$time_method = get_input('time_method', false);
$time_posted = get_input('time_posted', false);
$save_settings = get_input('save_settings', false);

$owner = elgg_get_logged_in_user_entity();


// Get the settings of the current user, if save_settings is true, that mean all settings are sended and we want to store it.
$user_river_options = json_decode(get_private_setting($owner->guid, 'deck_river_settings'), true);
$column_settings = $user_river_options[$tab][$column];

if ($save_settings) {
	$user_river_options[$tab][$column] = $column_settings = $save_settings;
	set_private_setting($owner->guid, 'deck_river_settings', json_encode($user_river_options));
}

$jsonexport = array();

// detect network
if ($column_settings['network'] == 'twitter') {
	$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
	$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');
	$account = get_entity($column_settings['account']);

	elgg_load_library('deck_river:twitter_async');
	$twitterObj = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret, $account->oauth_token, $account->oauth_token_secret);

	// Set options
	$options = array(
		'count' => 30,
	);

	if ($column_settings['type'] == 'get_listsStatuses') {
		$options['list_id'] = $column_settings['list_id'];
		$options['count'] = 31; // @todo why get_listsStatuses return only 29 items ?
	} else if ($column_settings['type'] == 'get_searchTweets-popular') {
		$options['q'] = $column_settings['search'];
		$options['result_type'] = 'popular';
	} else if ($column_settings['type'] == 'get_searchTweets') {
		$options['q'] = $column_settings['search'];
	}

	// refresh or more items
	if ($time_method == 'lower') {
		$options['since_id'] = $time_posted+1; // +1 for not repeat first river item
	} elseif ($time_method == 'upper') {
		$options['max_id'] = $time_posted-1; // -1 for not repeat last river item
	}

	try {
		$result = call_user_func(array($twitterObj, $column_settings['type']), $options);
	} catch(Exception $e) {
		$result = json_decode($e->getMessage())->errors[0];
	}

	// check result
	if ($result->code == 200) {
		$jsonexport['column_type'] = $column_settings['type'];

		if ($column_settings['type'] == 'get_searchTweets' || $column_settings['type'] == 'get_searchTweets-popular') {
			$results = $result->__get('response');
			$results = $results['statuses'];
		} else {
			$results = $result->__get('response');
		}

		$jsonexport['results'] = $results;
	} else {
		$key = 'deck_river:twitter:error:' . $result->code;
		if (elgg_echo($key) == $key) { // check if language string exist
			$jsonexport['column_error'] = elgg_echo('deck_river:twitter:error', array($result->code, $result->message));
		} else {
			$jsonexport['column_error'] = elgg_echo($key);
		}
		$jsonexport['results'] = '';
	}

} else {

	// Set column user settings
	switch ($column_settings['type']) {
		case 'all':
			break;
		case 'friends':
			$options['joins'][] = "JOIN {$dbprefix}entity_relationships r ON r.guid_two = rv.subject_guid";
			$options['joins'][] = "LEFT JOIN {$dbprefix}objects_entity o ON o.guid = rv.object_guid";
			$options['wheres'][] = "((r.relationship = 'friend' OR r.relationship = 'member') AND r.guid_one = '" . $owner->guid ."')";
			$options['wheres'][] = "(rv.subtype <> 'thewire' OR (o.description NOT LIKE '@%' AND o.description NOT LIKE '!%'))";
			break;
		case 'mine':
			$options['subject_guid'] = $owner->guid;
			break;
		case 'mention':
			$mention = '@'.$owner->name;
			$options['joins'][] = "JOIN {$dbprefix}objects_entity o ON o.guid = rv.object_guid";
			$options['joins'][] = "LEFT JOIN {$dbprefix}annotations a ON a.id = rv.annotation_id";
			$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings m ON m.id = a.value_id";
			$options['wheres'][] = "((rv.action_type <> 'comment' AND o.description REGEXP '"  . $mention . "[[:>:]]') OR (m.string REGEXP '"  . $mention . "[[:>:]]'))";
			//$options['wheres'][] = "((o.description LIKE '%@" . $owner->name . " %') OR (o.description LIKE '%@" . $owner->name . "') OR (m.string LIKE '%@" . $owner->name . " %') OR (m.string LIKE '%@" . $owner->name . "'))";
			//$options['wheres'][] = "((o.description REGEXP '@" . $owner->name . "([[:blank:]]|$|<)') OR (m.string REGEXP '@" . $owner->name . "([[:blank:]]|$|<)'))"; // FASTEST ? LIKE OR REGEXP ? WHY '<'
			break;
		case 'groups': // all groups
			$options['joins'][] = "JOIN {$dbprefix}entities e ON e.guid = rv.object_guid";
			$options['joins'][] = "LEFT JOIN {$dbprefix}entity_relationships r ON r.guid_one = '" . $owner->guid ."'";
			$options['wheres'][] = "((r.guid_two = e.container_guid AND r.relationship = 'member') OR (r.guid_two = e.guid AND r.relationship = 'member' AND rv.action_type = 'join'))";
			break;
		case 'group': // one group
			$options['joins'][] = "JOIN {$dbprefix}entities e ON e.guid = rv.object_guid";
			$options['wheres'][] = "(e.container_guid = " . $column_settings['group'] . " OR rv.object_guid = " . $column_settings['group'] . ")"; // for join group river object
			break;
		case 'group_mention':
			$group_name = get_entity($column_settings['group'])->name;
			$mention = '!'.$group_name;
			$options['joins'][] = "JOIN {$dbprefix}objects_entity o ON o.guid = rv.object_guid";
			$options['joins'][] = "LEFT JOIN {$dbprefix}annotations a ON a.id = rv.annotation_id";
			$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings m ON m.id = a.value_id";
			$options['wheres'][] = "((rv.action_type <> 'comment' AND o.description REGEXP '"  . $mention . "[[:>:]]') OR (m.string REGEXP '"  . $mention . "[[:>:]]'))";
			//$options['wheres'][] = "((o.description LIKE '%!" . $group_name . " %') OR (o.description LIKE '%!" . $group_name . "') OR (m.string LIKE '%!" . $group_name . "') OR (m.string LIKE '%!" . $group_name . "'))";
			break;
		case 'search':
			$options['joins'][] = "JOIN {$dbprefix}objects_entity o ON o.guid = rv.object_guid";
			$options['wheres'][] = "(o.description REGEXP '(" . implode('|', $column_settings['search']) . ")')";
			break;
		default:
			$params = array('owner' => $owner->guid, 'query' => 'activity');
			$result = elgg_trigger_plugin_hook('deck-river', "column:{$column_settings['type']}", $params);
			$result['column_type'] = $column_settings['type'];
			echo json_encode($result);
			return;
			break;
	}
	$options['title'] = $column_settings['title'];
	$options['types_filter'] = $column_settings['types_filter'];
	$options['subtypes_filter'] = $column_settings['subtypes_filter'];


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

	$jsonexport['results'] = array();
	if (!empty($items)) {
		foreach ($items as $item) {
			if (elgg_view_exists($item->view, 'json')) {
				elgg_view($item->view, array('item' => $item, 'mention' => $mention), '', '', 'json'); // this view fill the global $jsonexport
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

		// not first or second login
		if ($owner->prev_last_login != 0) {
		} else if($owner->last_login != 0) { // second login

		} else { // first login
		}

		// @todo should be on a hook or view to be overridden
		if (function_exists('get_dep_from_group_guid')) {
			$dep = get_dep_from_group_guid($owner->location);
			$echo = elgg_echo('deck_river:helper:'.$column_settings['type'], array($owner->location, $dep));
		}

		$jsonexport['results'] = '<table height="100%" width="100%"><tr><td class="helper">'. $echo . '</td></tr></table>';
	}


	$jsonexport['column_type'] = $column_settings['type'];

}

echo json_encode($jsonexport);
