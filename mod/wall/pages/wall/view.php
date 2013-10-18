<?php
/**
 * View individual wall post
 */

$post = get_entity(get_input('guid'), 'object');
if (!$post) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}
$owner = $post->getOwnerEntity();
if (!$owner) {
	forward();
}
$to = get_entity($post->to_guid, 'user');

$title = elgg_echo('wall:singleview', array($owner->name, $to->name));

elgg_push_breadcrumb($title);

$content = elgg_view_entity($post, array('full_view' => true));
$content .= elgg_view_comments($post);

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
