<?php 
/**
 * Save webinar form
 *
 * @package Elgg.Webinar
 */
$save_button = '';
$delete_button = '';

if ($vars['guid']) {
	// add a delete button if editing
	$delete_url = "action/webinar/delete?guid={$vars['guid']}";
	$delete_button = elgg_view('output/confirmlink', array(
			'href' => $delete_url,
			'text' => elgg_echo('delete'),
			'class' => 'elgg-button elgg-button-delete elgg-state-disabled float-alt'
	));
}

$save_button = elgg_view('input/submit', array(
		'value' => elgg_echo('save'),
		'name' => 'save',
));

$action_buttons = $save_button . $delete_button;

$title_label = elgg_echo('webinar:title');
$title_input = elgg_view('input/text', array(
		'name' => 'title',
		'id' => 'webinar_title',
		'value' => $vars['title']
));

$description_label = elgg_echo('webinar:description');
$description_input = elgg_view('input/longtext', array(
		'name' => 'description',
		'id' => 'webinar_description',
		'value' => $vars['description']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
		'name' => 'access_id',
		'id' => 'webinar_access_id',
		'value' => $vars['access_id']
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
		'name' => 'tags',
		'id' => 'webinar_tags',
		'value' => $vars['tags']
));

$status_label = elgg_echo('webinar:status');
$status_input = elgg_view('input/dropdown', array(
		'name' => 'status',
		'id' => 'webinar_status',
		'value' => $vars['status'],
		'options_values' => array(
				'upcoming' => elgg_echo('webinar:status:upcoming'),
				'running' => elgg_echo('webinar:status:running'),
				'done' => elgg_echo('webinar:status:done'),
				'cancel' => elgg_echo('webinar:status:cancel'),
		)
));

$fee_label = elgg_echo('webinar:fee');
$fee_input = elgg_view('input/text', array(
		'name' => 'fee',
		'id' => 'webinar_fee',
		'value' => $vars['fee'],
));

if ( elgg_is_admin_logged_in() ) {
	$server_url_label = elgg_echo('webinar:server_url');
	$server_url_input = elgg_view('input/text', array(
			'name' => 'server_url',
			'id' => 'webinar_server_url',
			'value' => $vars['server_url']
	));
	
	$server_salt_label = elgg_echo('webinar:server_salt');
	$server_salt_input = elgg_view('input/text', array(
			'name' => 'server_salt',
			'id' => 'webinar_server_salt',
			'value' => $vars['server_salt']
	));
	
	$admin_pwd_label = elgg_echo('webinar:admin_pwd');
	$admin_pwd_input = elgg_view('input/text', array(
			'name' => 'admin_pwd',
			'id' => 'webinar_admin_pwd',
			'value' => $vars['admin_pwd']
	));
	
	$user_pwd_label = elgg_echo('webinar:user_pwd');
	$user_pwd_input = elgg_view('input/text', array(
			'name' => 'user_pwd',
			'id' => 'webinar_user_pwd',
			'value' => $vars['user_pwd']
	));
	
	
	// if editing, allow admin to change logout url
	if ($vars['logout_url']) {
		$logout_url_label = elgg_echo('webinar:logout_url');
		$logout_url_input = elgg_view('input/text', array(
				'name' => 'logout_url',
				'id' => 'webinar_logout_url',
				'value' => $vars['logout_url']
		));
	}
}else{
	/*$admin_pwd_input = elgg_view('input/hidden', array('name' => 'webinar_admin_pwd', 'value' => $vars['admin_pwd']));
	$user_pwd_input = elgg_view('input/hidden', array('name' => 'webinar_user_pwd', 'value' => $vars['user_pwd']));
	$server_salt_input = elgg_view('input/hidden', array('name' => 'webinar_server_salt', 'value' => $vars['server_salt']));
	$server_url_input = elgg_view('input/hidden', array('name' => 'webinar_server_url', 'value' => $vars['server_url']));
	if ($vars['logout_url']) {
		$logout_url_input = elgg_view('input/hidden', array('name' => 'webinar_logout_url', 'value' => $vars['logout_url']));
	}
	$user_bloc = $admin_pwd_input . $user_pwd_input . $server_salt_input . $server_url_input . $logout_url_input; */
}

$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
if ($vars['guid']) {
	$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
}


echo <<<___HTML

<div>
	<label for="webinar_title">$title_label</label>
	$title_input
</div>

<label for="webinar_description">$description_label</label>
$description_input
<br />

<div>
	<label for="webinar_access_id">$access_label</label>
	$access_input
</div>

<div>
	<label for="webinar_tags">$tags_label</label>
	$tags_input
</div>

<div>
	<label for="webinar_fee">$fee_label</label>
	$fee_input
</div>

<div>
	<label for="webinar_status">$status_label</label>
	$status_input
</div>

<div>
	<label for="webinar_welcome_msg">$welcome_msg_label</label>
	$welcome_msg_input
</div>

___HTML;
if ( elgg_is_admin_logged_in() ) { echo <<<___HTML

<div>
	<label for="webinar_logout_url">$logout_url_label</label>
	$logout_url_input
</div>

<div>
	<label for="webinar_server_url">$server_url_label</label>
	$server_url_input
</div>

<div>
	<label for="webinar_server_salt">$server_salt_label</label>
	$server_salt_input
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
} echo <<<___HTML
<div class="elgg-foot">
	<div class="elgg-subtext mbm">
	$save_status <span class="blog-save-status-time">$saved</span>
	</div>

	$user_bloc
	$guid_input
	$container_guid_input

	$action_buttons
</div>

___HTML;
