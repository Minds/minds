<?php
/**
 * Webinar plugin settings
 */
	$server_url_label = elgg_echo('webinar:server_url');
	$server_url_input = elgg_view('input/text', array(
			'name' => 'params[server_url]',
			'id' => 'webinar_server_url',
			'value' => $vars['entity']->server_url
	));
	
	$server_salt_label = elgg_echo('webinar:server_salt');
	$server_salt_input = elgg_view('input/text', array(
			'name' => 'params[server_salt]',
			'id' => 'webinar_server_salt',
			'value' => $vars['entity']->server_salt
	));
	
	$admin_pwd_label = elgg_echo('webinar:admin_pwd');
	$admin_pwd_input = elgg_view('input/text', array(
			'name' => 'params[admin_pwd]',
			'id' => 'webinar_admin_pwd',
			'value' => $vars['entity']->admin_pwd
	));
	
	$user_pwd_label = elgg_echo('webinar:user_pwd');
	$user_pwd_input = elgg_view('input/text', array(
			'name' => 'params[user_pwd]',
			'id' => 'webinar_user_pwd',
			'value' => $vars['entity']->user_pwd
	));
	
echo <<<___HTML


<div>
	<label for="webinar_server_url">$server_url_label</label>
	$server_url_input
</div>

<div>
	<label for="webinar_server_salt">$server_salt_label</label>
	$server_salt_input
</div>

<div>
	<label for="webinar_logout_url">$logout_url_label</label>
	$logout_url_input
</div>

<div>
	<label for="webinar_admin_pwd">$admin_pwd_label</label>
	$admin_pwd_input
</div>

<div>
	<label for="webinar_user_pwd">$user_pwd_label</label>
	$user_pwd_input
</div>

___HTML;

