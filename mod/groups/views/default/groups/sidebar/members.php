<?php
/**
 * Group members sidebar
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 * @uses $vars['limit']  The number of members to display
 */

$limit = elgg_extract('limit', $vars, 7);

$all_link = elgg_view('output/url', array(
	'href' => 'groups/members/' . $vars['entity']->guid,
	'text' => elgg_echo('groups:members:more'),
	'is_trusted' => true,
));

$members = $vars['entity']->getMembers($limit,'');

$body = elgg_view_entity_list($members, array('list_type' => 'gallery',
                'gallery_class' => 'elgg-gallery-users', 'pagination'=>false, 'size'=>'small'));
 
$body .= "<div class='center mts'>$all_link</div>";

echo elgg_view_module('aside', elgg_echo('groups:members'), $body);
