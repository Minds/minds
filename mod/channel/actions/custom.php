<?php
/**
 * CUSTOM CHANNEL ACTION
 */

$guid = get_input('guid');
$user = get_entity($guid, 'user');

$background_colour = get_input('background_colour');
$background_repeat = get_input('background_repeat');
$background_attachment = get_input('background_attachment');
$background_h_pos = get_input('background_h_pos');
$background_v_pos = get_input('background_v_pos');
$text_colour = get_input('text_colour');
$link_colour = get_input('link_colour');
$widget_bg = get_input('widget_bg');
$widget_head_title_color = get_input('widget_head_title_color');
$widget_body_text = get_input('widget_body_text');


if(get_input('remove_bg') == 'yes'){
	
	$thumb = new ElggFile;
	$thumb->owner_guid = $guid;
	$thumb->setFilename('profile/background_thumb.jpg');
	if($thumb->exists())
	$thumb->delete();
	
	$file = new ElggFile;
	$file->owner_guid = $guid;
	$file->setFilename('profile/background.jpg');
	if($file->exists())
	$file->delete();
	
		
	$user->background = false;
	$user->save();
	
	system_message(elgg_echo('channel:custom:saved'));
	forward(REFERRER);

}
if(get_input('reset') == 'yes'){
	$user->background_colour = $user->background_repeat = $user->background_attachment = $user->background_h_pos = $user->background_v_pos = $user->text_colour = $user->link_colour = $user->background= null;
	$user->save();
	//channel_custom_remove_bg();
	forward(REFERRER); 
}

elgg_make_sticky_form('channel_custom');

if ($guid) {
		
	if($_FILES['background']['name']){
		$filestorename = 'background.jpg';
		
		//$bg = get_resized_image_from_uploaded_file('background', 5000, 5000, false, false);
		
		$file = new ElggFile();
		$file->owner_guid = $guid;
		$file->setFilename("profile/background.jpg");
		$file->open('write');
		$file->write(get_uploaded_file('background'));
		$file->close();
		
		$thumb = new ElggFile();
		$thumb->owner_guid = $guid;
		$thumb->setFilename("profile/background_thumb.jpg");
		$thumb->open('write');
		$thumb->write(get_resized_image_from_uploaded_file('background', 150, 150, false));
		$thumb->close();
		
		$user->background = true;
	}
		
		//if($background_colour)
			$user->background_colour = $background_colour;
		//if($background_repeat)
			$user->background_repeat = $background_repeat;
		//if($background_attachment)	
			$user->background_attachment = $background_attachment;
		//if($background_h_pos)
			$user->background_h_pos = $background_h_pos;
		//if($background_v_pos)
			$user->background_v_pos = $background_v_pos;
		//if($text_colour)
			$user->text_colour = $text_colour;
		//if($link_colour)
			$user->link_colour = $link_colour;
		//bg_timestamp for updating cached content
			$user->background_timestamp = time();
		
		$user->widget_bg = $widget_bg;
		$user->widget_head_title_color = $widget_head_title_color;
		$user->widget_body_text = $widget_body_text;
		
		$user->save();

}
system_message(elgg_echo('channel:custom:saved'));
forward(REFERRER);