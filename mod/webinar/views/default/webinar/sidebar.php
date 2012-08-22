<?php
/**
 * Webinar sidebar
 *
 * @package Blog
 */

// fetch & display latest comments
if ($vars['page'] == 'all') {
	echo elgg_view('page/elements/comments_block', array(
		'subtypes' => 'webinar',
	));
} elseif ($vars['page'] == 'owner') {
	echo elgg_view('page/elements/comments_block', array(
		'subtypes' => 'webinar',
		'owner_guid' => elgg_get_page_owner_guid(),
	));
}

if ($vars['page'] != 'friends') {
	echo elgg_view('page/elements/tagcloud_block', array(
		'subtypes' => 'webinar',
		'owner_guid' => elgg_get_page_owner_guid(),
	));
}
