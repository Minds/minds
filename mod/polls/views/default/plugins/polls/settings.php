<?php
$group_options = array(elgg_echo('polls:settings:group_polls_default')=>'yes_default',
elgg_echo('polls:settings:group_polls_not_default')=>'yes_not_default',
elgg_echo('polls:settings:no')=>'no',
);

$yn_options = array(
	elgg_echo('polls:settings:yes') => 'yes',
	elgg_echo('polls:settings:no') => 'no',
);

$polls_create_in_river = elgg_get_plugin_setting('create_in_river', 'polls');
if (!$polls_create_in_river) {
	$polls_create_in_river = 'yes';
}

$body .= elgg_echo('polls:settings:create_in_river:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[create_in_river]','value'=>$polls_create_in_river,'options'=>$yn_options));

$body .= '<br />';

$polls_vote_in_river = elgg_get_plugin_setting('vote_in_river', 'polls');
if (!$polls_vote_in_river) {
	$polls_vote_in_river = 'yes';
}

$body .= elgg_echo('polls:settings:vote_in_river:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[vote_in_river]','value'=>$polls_vote_in_river,'options'=>$yn_options));

$body .= '<br />';

$polls_group_polls = elgg_get_plugin_setting('group_polls', 'polls');
if (!$polls_group_polls) {
	$polls_group_polls = 'yes_default';
}

$body .= elgg_echo('polls:settings:group:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[group_polls]','value'=>$polls_group_polls,'options'=>$group_options));

$body .= '<br />';

$polls_group_access_options = array(elgg_echo('polls:settings:group_access:admins')=>'admins',
	elgg_echo('polls:settings:group_access:members')=>'members',
);

$polls_group_access = elgg_get_plugin_setting('group_access', 'polls');
if (!$polls_group_access) {
	$polls_group_access = 'admins';
}

$body .= elgg_echo('polls:settings:group_access:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[group_access]','value'=>$polls_group_access,'options'=>$polls_group_access_options));

$body .= '<br />';

$polls_site_access_options = array(elgg_echo('polls:settings:site_access:admins')=>'admins',
	elgg_echo('polls:settings:site_access:all')=>'all',
);

$polls_site_access = elgg_get_plugin_setting('site_access', 'polls');
if (!$polls_site_access) {
	$polls_site_access = 'all';
}

$body .= elgg_echo('polls:settings:site_access:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[site_access]','value'=>$polls_site_access,'options'=>$polls_site_access_options));

$body .= '<br />';

$polls_front_page = elgg_get_plugin_setting('front_page', 'polls');
if (!$polls_front_page) {
	$polls_front_page = 'no';
}

$body .= elgg_echo('polls:settings:front_page:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[front_page]','value'=>$polls_front_page,'options'=>$yn_options));

$body .= '<br />';

echo $body;
