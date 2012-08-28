<?php
/**
 * CUSTOM CHANNEL ACTION
 */

$guid = get_input('guid');
$user = get_entity($guid);

$background_colour = get_input('background_colour');
$background_repeat = get_input('background_repeat');
$background_h_pos = get_input('background_h_pos');
$background_v_pos = get_input('background_v_pos');
$text_colour = get_input('text_colour');
$link_colour = get_input('link_colour');

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
		//if($background_h_pos)
			$user->background_h_pos = $background_h_pos;
		//if($background_v_pos)
			$user->background_v_pos = $background_v_pos;
		//if($text_colour)
			$user->text_colour = $text_colour;
		//if($link_colour)
			$user->link_colour = $link_colour;
		
		$user->save();

}
system_message(elgg_echo('channel:custom:saved'));
forward(REFERRER);