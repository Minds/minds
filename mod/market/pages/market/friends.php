<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */
		
// Get input
$offset = get_input('offset', 0);
$owner_name = get_input('username');
$owner = get_user_by_username($owner_name);

elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
elgg_push_breadcrumb($owner->name, "market/owned/{$owner->username}");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

//set the title
if ($owner->guid == elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('market:friends:title');
} else {
	$title = elgg_echo('market:user:friends:title', array($owner->name));
}

if (!$friends = get_user_friends($owner->guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
	$content = elgg_echo('friends:none:you');
} else {
	$options = array(
			'type' => 'object',
			'subtype' => 'market',
			'full_view' => false,
			'pagination' => true,
			'limit' => 5,
			'view_type_toggle' => FALSE,
			);

	foreach ($friends as $friend) {
		$options['container_guids'][] = $friend->getGUID();
	}

	$content = elgg_list_entities_from_metadata($options);

}

		
if (empty($content)) {
	$content = elgg_echo('market:none:found');
}
		
// Show market sidebar
$sidebar = elgg_view("market/sidebar");

$params = array(
		'filter' => false,
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
