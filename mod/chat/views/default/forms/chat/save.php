<?php
/**
 * Edit chat form
 *
 * @package Chat
 */

$title_label = elgg_echo('chat:title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $vars['title'],
));

$message_label = '';
$message_title = '';
// View message box only when creating a new chat
if (!$vars['guid']) {
	$message_label = elgg_echo('chat:message');
	$message_input = elgg_view('input/longtext', array(
		'name' => 'message',
		'value' => $vars['description'],
	));
}

$users_label = elgg_echo('chat:members:manage');
$users_input = elgg_view('input/userpicker', array(
	'name' => 'users',
	'value' => $vars['members'],
));

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['guid'],
));

$container_guid_input = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit',
	'value' => elgg_echo('save'),
));

$form = <<<FORM

<div>
	<label>$title_label</label>
	$title_input
</div>

<div>
	<label>$message_label</label>
	$message_input
</div>

<div>
	<label>$users_label</label>
	$users_input
</div>

<div class="elgg-foot">
	$container_guid_input
	$guid_input
	$submit_input
</div>

FORM;

echo $form;