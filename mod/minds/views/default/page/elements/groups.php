<?php
/**
 * Elgg file widget view
 *
 * @package ElggFile
 */


$num = 5;

$options = array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$title = "<h2>" .elgg_echo('groups:widget:membership') . "</h2>";
$content = "<div class=\"is-groups-element\">" . $title . elgg_list_entities_from_relationship($options) . "</div>";

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
