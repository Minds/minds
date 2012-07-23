<?php
/**
 * Edit chat message form
 *
 * @package Chat
 */

$message_input = elgg_view('input/longtext', array(
	'name' => 'message',
	'value' => $vars['description'],
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