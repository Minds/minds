<?php
/**
 * Custom Page
 */

elgg_load_js('minicolors');
elgg_load_css('minicolors');
 
$user = $vars['entity'];

if($user->background){
	$upload_label = elgg_echo('channel:custom:reupload');
	$upload_input .= elgg_view('input/file', array(
		'name' => 'background',
	));
} else {
	$upload_label = elgg_echo('channel:custom:upload');
	$upload_input = elgg_view('input/file', array(
		'name' => 'background',
	));
}
//@todo add a delete background link
$bg_repeat_label = elgg_echo('channel:custom:background:repeat');
$bg_repeat_input = elgg_view('input/dropdown', array(
		'name' => 'background_repeat',
		'options_values' => array(	'repeat' => elgg_echo('channel:custom:background:repeat:repeat'),
									'no-repeat' => elgg_echo('channel:custom:background:repeat:no-repeat'),
									'repeat-x' => elgg_echo('channel:custom:background:repeat:repeat-x'),
									'repeat-y' => elgg_echo('channel:custom:background:repeat:repeat-y')
								),
		'value' => $user->background_repeat
	));

$bg_colour_label = elgg_echo('channel:custom:color:background');
$bg_colour_input = elgg_view('input/text', array(
		'name' => 'background_colour',
		'value' => $user->background_colour,
		'class' => 'colorpicker',
		'size' => 1
	));

$text_colour_label = elgg_echo('channel:custom:color:text');
$text_colour_input = elgg_view('input/text', array(
		'name' => 'text_colour',
		'value' => $user->text_colour,
		'class' => 'colorpicker',
		'size' => 1
	));
	
$link_colour_label = elgg_echo('channel:custom:color:link');
$link_colour_input = elgg_view('input/text', array(
		'name' => 'link_colour',
		'value' => $user->link_colour,
		'class' => 'colorpicker',
		'size' => 1
	));


$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $user->guid,
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
	<label>$upload_label</label>
	$upload_input
</div>

<div>
	<label>$bg_repeat_label</label>
	$bg_repeat_input
</div>

<div>
	<label>$bg_colour_label</label>
	$bg_colour_input
</div>

<div>
	<label>$text_colour_label</label>
	$text_colour_input
</div>

<div>
	<label>$link_colour_label</label>
	$link_colour_input
</div>

<div class="elgg-foot">
	$container_guid_input
	$guid_input
	$submit_input
</div>

FORM;

echo $form;

?>
<script> 
	$(".colorpicker").miniColors({
					letterCase: 'uppercase',				
				});		
</script>