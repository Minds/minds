<?php
/**
 * Elgg file widget view
 *
 * @package ElggFile
 */


$num = $vars['entity']->num_display;

$user = elgg_get_page_owner_entity();
$users_group_guids = $user->group_guids ? unserialize($user->group_guids) : array();;
$content = elgg_list_entities(array('guids'=>$users_group_guids));

echo $content;

if ($content) {
	$url = "groups/member/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('groups:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('groups:none');
}
