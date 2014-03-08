<?php
/**
 * User webinar widget display view
 * 
 * @package Elgg.Webinar
 */

$num = $vars['entity']->webinar_num;

$options = array(
	'type' => 'object',
	'subtype' => 'webinar',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$webinar_url = "webinar/owner/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $webinar_url,
		'text' => elgg_echo('webinar:widget:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('webinar:widget:no');
}
