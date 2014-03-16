<?php
/**
 * Members sidebar
 */

// Tag search
$params = array(
	'method' => 'get',
	'action' => elgg_get_site_url() . 'channels/search/tag',
	'disable_security' => true,
);

$body = elgg_view_form('members/tag_search', $params);

echo elgg_view_module('aside', elgg_echo('channels:searchtag'), $body);

// name search
$params = array(
	'method' => 'get',
	'action' => elgg_get_site_url() . 'channels/search/name',
	'disable_security' => true,
);
$body = elgg_view_form('members/name_search', $params);

echo elgg_view_module('aside', elgg_echo('channels:searchname'), $body);

echo elgg_view('page/elements/ads', array('type'=>'content-side'));