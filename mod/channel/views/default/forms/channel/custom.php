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

$upload_label = elgg_echo('channel:custom:upload');
$upload_input = elgg_view('input/file', array(
	'name' => 'background',
));

$form_vars = channel_custom_vars($user);

if($user->background){
	$upload_input .= elgg_view('output/url', array(
		'href' => 'action/channel/custom?remove_bg=yes&guid='.$user->guid,
		'text' => elgg_echo('channel:custom:background:remove'),
		'is_action' =>true
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
		'value' => $form_vars['background_repeat']
	));
	
$bg_attachment_label = elgg_echo('channel:custom:background:attachment');
$bg_attachment_input = elgg_view('input/dropdown', array(
		'name' => 'background_attachment',
		'options_values' => array(	'fixed' => elgg_echo('channel:custom:background:attachment:fixed'),
									'scoll' => elgg_echo('channel:custom:background:attachment:scroll'),
								),
		'value' => $form_vars['background_attachment']
	));
	
$bg_colour_label = elgg_echo('channel:custom:color:background');
$bg_colour_input = elgg_view('input/text', array(
		'name' => 'background_colour',
		'value' => $form_vars['background_colour'], 
		'class' => 'colorpicker',
		'size' => 1
	));

$h1_colour_label = elgg_echo('channel:custom:color:h1');
$h1_colour_input = elgg_view('input/text', array(
		'name' => 'h1_colour',
		'value' => $form_vars['h1_colour'],
		'class' => 'colorpicker',
		'size' => 1
	));
	
$h3_colour_label = elgg_echo('channel:custom:color:h3');
$h3_colour_input = elgg_view('input/text', array(
		'name' => 'h3_colour',
		'value' => $form_vars['h3_colour'],
		'class' => 'colorpicker',
		'size' => 1
	));

$menu_link_colour_label = elgg_echo('channel:custom:color:menu_link');
$menu_link_colour_input = elgg_view('input/text', array(
                'name' => 'menu_link_colour',
                'value' => $form_vars['menu_link_colour'],
                'class' => 'colorpicker',
                'size' => 1
        ));

$channel_brief_description_label = elgg_echo('channel:edit:brief:label');
$channel_brief_description_input = elgg_view('input/text', array(
		'name' => 'briefdescription',
		'value' => $form_vars['briefdescription']
));
$channel_location_label = elgg_echo('channel:edit:location:label');
$channel_location_input = elgg_view('input/text', array(
		'name' => 'location',
		'value' =>  $form_vars['location']
));
$channel_email_label = elgg_echo('channel:edit:email:label');
$channel_email_input = elgg_view('input/text', array(
                'name' => 'contactemail',
                'value' =>  $form_vars['contactemail']
));
$channel_description_label = elgg_echo('channel:edit:description:label');
$channel_description_input = elgg_view('input/plaintext', array(
                'name' => 'description',
                'value' =>  $form_vars['description']
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

$reset_input = elgg_view('output/url', array(	
										'text' => elgg_echo('channel:custom:reset'),
										'href' => "action/channel/custom?reset=yes&guid=" . $user->guid,
										'class' => 'elgg-button elgg-button-cancel channel',
										'is_action' => true
									));

$form = <<<FORM
				
		<table>
			<tr>
				<td>
					<h3>$bg_header_label </h3>
				</td>
			</tr>
			<tr>
				<td class="label">
					$upload_label
				</td>
				<td>
					$upload_input
				</td>
			</tr>
			<tr>
				<td class="label">
					$bg_repeat_label
				</td>
				<td>
					$bg_repeat_input
				</td>
			</tr>
			<tr>
				<td class="label">
					$bg_attachment_label
				</td>
				<td>
					$bg_attachment_input
				</td>
			</tr>
			<tr>
				<td class="label">
					$bg_colour_label
				</td>
				<td>
					$bg_colour_input
				</td>
			</tr>
		</table>
			
	<table>
		<tr>
			<td>
				<h3> $text_header_label </h3>
			</td>
		</tr>
		<tr>
			<td class="label">
				$h1_colour_label
			</td>
			<td>
				$h1_colour_input
			</td>
		</tr>
		<tr>
			<td class="label">
				$h3_colour_label
			</td>
			<td>
				$h3_colour_input
			</td>
		</tr>
		<tr>
                        <td class="label">
                                $menu_link_colour_label
                        </td>
                        <td>
                                $menu_link_colour_input
                        </td>
                </tr>
	</table>
			
	<table>
		<tr>
			<td>
				<h3> $channel_info_label </h3>
			</td>
		</tr>
		<tr>
			<td class="label">
				$channel_brief_description_label
			</td>
			<td>
				$channel_brief_description_input
			</td>
		</tr>
		<tr>
			<td class="label">
				$channel_location_label
			</td>
			<td>
				$channel_location_input
			</td>
		</tr>
		<tr>
			<td class="label">
				$channel_email_label
			</td>
			<td>
				$channel_email_input
			</td>
		</tr>
		<tr>
			<td class="label">
				$channel_description_label
			</td>
			<td>
				$channel_description_input
			</td>
		</tr>
	</table>
		
	<div class="elgg-foot">
		$container_guid_input
		$guid_input
		$submit_input
		$reset_input
	</div>
	

FORM;

echo $form;

?>
