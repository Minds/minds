<?php
/**
 * Channel Custom Background
 * 
 * @author Mark Harding (mark@minds.com)
 */

gatekeeper();

$user = elgg_get_page_owner_entity();

if (!$user) {
	register_error(elgg_echo("profile:notfound"));
	forward();
}

// check if logged in user can edit this profile
if (!$user->canEdit()) {
	register_error(elgg_echo("profile:noaccess"));
	forward();
}

 
elgg_set_context('profile_edit');
 
$user = elgg_get_page_owner_entity();
 
$title = elgg_echo('channel:custom');

$content = elgg_view_form('channel/custom', array('enctype' => 'multipart/form-data'), array('entity' => $user));

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
