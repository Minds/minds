<?php
/**
 * Custom Page
 */

elgg_load_js('minicolors');
elgg_load_css('minicolors');
 
$user = $vars['entity'];

$bg_header_label = elgg_echo('channel:custom:bg');
$text_header_label = elgg_echo('channel:custom:text');
$widget_header_label = elgg_echo('channel:custom:widget');

if($user->background){
	$upload_label = elgg_echo('channel:custom:reupload');
	$upload_input .= elgg_view('input/file', array(
		'name' => 'background',
	));
	$upload_input .= elgg_view('output/url', array(
		'href' => 'action/channel/custom?remove_bg=yes&guid='.$user->guid,
		'text' => elgg_echo('channel:custom:background:remove'),
		'is_action' =>true
		));
} else {
	$upload_label = elgg_echo('channel:custom:upload');
	$upload_input = elgg_view('input/file', array(
		'name' => 'background',
	));
}

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
	
$bg_attachment_label = elgg_echo('channel:custom:background:attachment');
$bg_attachment_input = elgg_view('input/dropdown', array(
		'name' => 'background_attachment',
		'options_values' => array(	'fixed' => elgg_echo('channel:custom:background:attachment:fixed'),
									'scoll' => elgg_echo('channel:custom:background:attachment:scroll'),
								),
		'value' => $user->background_attachment
	));
	
$bg_colour_label = elgg_echo('channel:custom:color:background');
$bg_colour_input = elgg_view('input/text', array(
		'name' => 'background_colour',
		'value' => $user->background_colour ? $user->background_colour : '#FEFEFE',
		'class' => 'colorpicker',
		'size' => 1
	));

$text_colour_label = elgg_echo('channel:custom:color:text');
$text_colour_input = elgg_view('input/text', array(
		'name' => 'text_colour',
		'value' => $user->text_colour ? $user->text_colour : '#000',
		'class' => 'colorpicker',
		'size' => 1
	));
	
$link_colour_label = elgg_echo('channel:custom:color:link');
$link_colour_input = elgg_view('input/text', array(
		'name' => 'link_colour',
		'value' => $user->link_colour ? $user->link_colour : '#4690D6',
		'class' => 'colorpicker',
		'size' => 1
	));

$widget_bg_label = elgg_echo('channel:custom:widget:bg');
$widget_bg_input = elgg_view('input/text', array(
		'name' => 'widget_bg',
		'value' => $user->widget_bg ? $user->widget_bg : '#F2F2F2',
		'class' => 'colorpicker',
		'size' => 1
	));

$widget_head_title_color_label = elgg_echo('channel:custom:widget:head:title:color');
$widget_head_title_color_input = elgg_view('input/text', array(
		'name' => 'widget_head_title_color',
		'value' => $user->widget_head_title_color ? $user->widget_head_title_color : '#666666',
		'class' => 'colorpicker',
		'size' => 1
	));
	
$widget_body_text_label = elgg_echo('channel:custom:widget:body_text');
$widget_body_text_input = elgg_view('input/text', array(
		'name' => 'widget_body_text',
		'value' => $user->widget_body_text ? $user->widget_body_text : '#000000',
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
		<h3> $bg_header_label </h3>
	</div>
	
	<div>
		<label>$upload_label</label>
		$upload_input
	</div>
	
	<div>
		<label>$bg_repeat_label</label>
		$bg_repeat_input
	</div>
	
	<div>
		<label>$bg_attachment_label</label>
		$bg_attachment_input
	</div>
	
	<div>
		<label>$bg_colour_label</label>
		$bg_colour_input
	</div>
	
	<div>
		<h3> $text_header_label </h3>
	</div>
	
	<div>
		<label>$text_colour_label</label>
		$text_colour_input
	</div>
	
	<div>
		<label>$link_colour_label</label>
		$link_colour_input
	</div>
	
	<div>
		<h3> $widget_header_label </h3>
	</div>
	
	<div>
		<label>$widget_bg_label</label>
		$widget_bg_input
	</div>
	
	<div>
		<label>$widget_head_title_color_label</label>
		$widget_head_title_color_input
	</div>
	
	<div>
		<label>$widget_body_text_label</label>
		$widget_body_text_input
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