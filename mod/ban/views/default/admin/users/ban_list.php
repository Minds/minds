<?php
/**
 * List banned users
 *
 * @todo figure out how to display users in a table
 *
 * @uses $vars['limit']
 * @uses $vars['pagination']
 */

// elgg makes it hard to list entities with an alternate view
elgg_register_plugin_hook_handler('view', 'user/default', 'banned_user_view');

$pagination = elgg_extract('pagination', $vars, true);
$limit = elgg_extract('limit', $vars, get_input('limit', 10));

$joins = array(
	"JOIN {$CONFIG->dbprefix}users_entity u on e.guid = u.guid",
);

$params = array(
	'type'   => 'user',
	'joins'  => $joins,
	'wheres' => array("u.banned = 'yes'"),
	'limit' => $limit,
	'full_view' => false,
	'pagination' => $pagination,
);


$list = elgg_list_entities_from_metadata($params);
if ($list) {
	echo $list;
} else {
	echo elgg_echo('ban:none');
}

elgg_unregister_plugin_hook_handler('view', 'user/default', 'banned_user_view');
