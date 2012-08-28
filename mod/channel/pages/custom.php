<?php
/**
 * Channel Custom Background
 * 
 * @author Mark Harding (mark@minds.com)
 */

gatekeeper();
 
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
