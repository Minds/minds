<?php

$msg = $vars['msg'];
$consumer_key = $vars['consumer_key'];
$return_to = $vars['return_to'];
$user_auth = $vars['user_auth'];
$request_url = $vars['request_url'];
$access_url = $vars['access_url'];

if (array_key_exists('action', $vars)) {
	$action = $vars['action'];
} else {
	$action = $CONFIG->wwwroot . 'action/oauth/gettoken';
}

$consumer = oauth_lookup_consumer_entity($consumer_key);

if (!$msg) {
	// nice default message
	$site = get_entity($CONFIG->site_guid);
	$msg = sprintf(elgg_echo('oauth:tokenform'), $site, $consumer->name);
}

$submit = elgg_view('input/submit', array('value' => 'Get Token'));
$cons_key = elgg_view('input/hidden', array('name' => 'consumer_key', 'value' => $consumer_key));
$redir = elgg_view('input/hidden', array('name' => 'return_to', 'value' => $return_to));
$userauth = elgg_view('input/hidden', array('name' => 'user_auth', 'value' => $user_auth));
$request = elgg_view('input/hidden', array('name' => 'request_url', 'value' => $request_url));
$access = elgg_view('input/hidden', array('name' => 'access_url', 'value' => $access_url));

// wrap our message in a paragraph
$explain = '<p>' . $msg . '</p>';

$form = elgg_view('input/form', array('action' => $action,
				      'body' => $explain
				      . $redir 
				      . $cons_key 
				      . $userauth
				      . $request
				      . $access
				      . $submit));
	
echo $form;

?>