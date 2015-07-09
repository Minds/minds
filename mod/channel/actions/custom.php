<?php
/**
 * CUSTOM CHANNEL ACTION
 */

$guid = get_input('guid');
$user = get_entity($guid, 'user');

if(!$user->canEdit()){
    forward(REFERRER);
}

if(get_input('remove_bg') == 'yes'){
	
	$thumb = new ElggFile;
	$thumb->owner_guid = $guid;
	$thumb->setFilename('profile/background_thumb.jpg');
	if($thumb->exists())
		@unlink($thumb->getFilenameOnFilestore());
	
	$file = new ElggFile;
	$file->owner_guid = $guid;
	$file->setFilename('profile/background.jpg');
	if($file->exists())
		@unlink($thumb->getFilenameOnFilestore());
		
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

	$form_vars = minds\plugin\channel\start::channel_custom_vars($user);
	foreach($form_vars as $k => $v){
		$user->$k = get_input($k, $v);
	}
	
	$user->background_timestamp = time();
		
	$guid = $user->save();
	if($user->guid == $_SESSION['user']->guid){
        session_regenerate_id(true);
        //   $_SESSION['user'] = $user;
    }
}
system_message(elgg_echo('channel:custom:saved'));
forward($user->getURL());
