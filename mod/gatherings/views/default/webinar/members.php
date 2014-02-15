<?php
/**
 * Webinar members sidebar
*
* @package Elgg.Webinar
*
* @uses $vars['entity'] Webinar entity
* @uses $vars['limit']  The number of members to display
* @uses $vars['relationship']  The relationship with the webinar <registred|attendee>
*/

$limit = elgg_extract('limit', $vars, 10);
$relationship = elgg_extract('relationship', $vars, 'registered');

$all_link = elgg_view('output/url', array(
		'href' => "webinar/$relationship/{$vars['entity']->guid}",
		'text' => elgg_echo("webinar:members:more"),
		'is_trusted' => true,
));

$body = elgg_list_entities_from_relationship(array(
		'relationship' => $relationship,
		'relationship_guid' => $vars['entity']->guid,
		'inverse_relationship' => true,
		'types' => 'user',
		'limit' => $limit,
		'list_type' => 'gallery',
		'gallery_class' => 'elgg-gallery-users',
));

if($body){
	$body .= "<div class='center mts'>$all_link</div>";
}else{
	$body = elgg_echo("webinar:members:no");
}
echo elgg_view_module('aside', elgg_echo("webinar:members:$relationship"), $body);
