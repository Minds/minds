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
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$title = elgg_echo('groups:widget:membership');
$content = "<div class=\"is-groups-element\">" . elgg_list_entities_from_relationship($options) . "</div>";
if($content){
	echo elgg_view_module('featured', $title, $content);
}
