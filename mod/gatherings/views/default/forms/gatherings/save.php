<?php 
/**
 * Save gatherings
 *
 */
 
$save_button = '';
$delete_button = '';

if (isset($vars['guid'])) {
	// add a delete button if editing
	$delete_url = "action/gatherings/delete?guid={$vars['guid']}";
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

$title_label = elgg_echo('gatherings:title');
$title_input = elgg_view('input/text', array(
		'name' => 'title',
		'id' => 'gatherings_title',
		'value' => $vars['title']
));

$description_label = elgg_echo('gatherings:description');
$description_input = elgg_view('input/longtext', array(
		'name' => 'description',
		'id' => 'gatherings_description',
		'value' => $vars['description']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
		'name' => 'access_id',
		'id' => 'gatherings_access_id',
		'value' => $vars['access_id']
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
		'name' => 'tags',
		'id' => 'gatherings_tags',
		'value' => $vars['tags']
));

$status_label = elgg_echo('gatherings:status');
$status_input = elgg_view('input/dropdown', array(
		'name' => 'status',
		'id' => 'gatherings_status',
		'value' => $vars['status'],
		'options_values' => array(
				'upcoming' => elgg_echo('gatherings:status:upcoming'),
				'running' => elgg_echo('gatherings:status:running'),
				'done' => elgg_echo('gatherings:status:done'),
				'cancel' => elgg_echo('gatherings:status:cancel'),
		)
));

$fee_label = elgg_echo('gatherings:fee');
$fee_input = elgg_view('input/text', array(
		'name' => 'fee',
		'id' => 'gatherings_fee',
		'value' => $vars['fee'],
));

$enterprise_label = elgg_echo('gatherings:enterprise');
$enterprise_description = elgg_echo('gatherings:enterprise:description');
$enterprise_input = elgg_view('input/checkbox', array(
		'name' => 'enterprise',
		'id' => 'gatherings_enteprise',
		'checked' => $vars['enterprise'] ? 'checked' : false,
));


$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
if ($vars['guid']) {
	$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
}


echo <<<___HTML

<div>
	<label for="gatherings_title">$title_label</label>
	$title_input
</div>

<label for="gatherings_description">$description_label</label>
$description_input
<br />

<div>
	<label for="gatherings_access_id">$access_label</label>
	$access_input
</div>

<div>
	<label for="gatherings_tags">$tags_label</label>
	$tags_input
</div>

<div>
	<label for="gatherings_fee">$fee_label</label>
	$fee_input
</div>

<div>
	<label for="gatherings_enterprise">$enterprise_label</label>
	$enterprise_input
	<span>$enterprise_description</span>
</div>

<div>
	<label for="gatherings_status">$status_label</label>
	$status_input
</div>

<div>
	<label for="gatherings_welcome_msg">$welcome_msg_label</label>
	$welcome_msg_input
</div>

___HTML;
if ( elgg_is_admin_logged_in() ) { echo <<<___HTML

<div>
	<label for="gatherings_logout_url">$logout_url_label</label>
	$logout_url_input
</div>

<div>
	<label for="gatherings_server_url">$server_url_label</label>
	$server_url_input
</div>

<div>
	<label for="gatherings_server_salt">$server_salt_label</label>
	$server_salt_input
</div>

<div>
	<label for="gatherings_admin_pwd">$admin_pwd_label</label>
	$admin_pwd_input
</div>

<div>
	<label for="gatherings_user_pwd">$user_pwd_label</label>
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
