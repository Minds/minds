<?php
/**
 * Add chat members form
 *
 * @package Chat
 */

$members_label = elgg_echo('chat:members');

$members_input = elgg_view('input/userpicker', array());

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['guid'],
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit',
	'value' => elgg_echo('save'),
));

$form = <<<FORM

<div>
	<label>$members_label</label>
	$members_input
</div>

<div class="elgg-foot">
	$guid_input
	$submit_input
</div>

FORM;

echo $form;
