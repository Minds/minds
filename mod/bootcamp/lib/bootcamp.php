<?php

/**
 * Register a step that will show on the bootcamp page
 */
function bootcamp_register_step($params = array()){
	global $BOOTCAMP;
	
	if(!$params['name']){
		throw new Exception('No name');
		return false;
	}
	
	$info = new stdClass();
	$info->name = $params['name'];
	$info->title = $params['title'];
	$info->content = $params['content'];
	$info->href = $params['href'];
	$info->priority = $params['priority'];
	$info->completed = $params['completed'];
	$info->required = $params['required'];

	$BOOTCAMP[$params['name']] = $info;

	return true;
}

/**
 * Return a list of all steps that have been registered
 * 
 * @todo is this function really needed? /MH
 */
function bootcamp_get_steps(){
	global $BOOTCAMP;
	
	return $BOOTCAMP;
}

/** 
 * Return a specific step
 */
function bootcamp_get_step($name){
	
}

/**
 * Calculate how many steps a user has completed
 */
function bootcamp_calculate_progress(){
	$steps = bootcamp_get_steps();
	
	$completed = 0;
	foreach($steps as $step){
		if($step->completed){
			$completed++;
		} 
	}
	$percentage = (count($steps) / (int) $completed) *100;
	return $percentage;
}

/*
 * REGISTER THE STEPS WE WANT TO USE @todo MAKE THIS AVAILABLE IN PLUGIN SETTINGS
 */
$user = elgg_get_logged_in_user_entity();
bootcamp_register_step(	array(	'name'=> 'avatar',
								'title'=> elgg_echo('bootcamp:step:avatar:title'),
								'content'=> elgg_echo('bootcamp:step:avatar:content'),
								'href'=> elgg_get_site_url() . 'avatar/edit',
								'priority' => 1,
								'completed' => $user->icontime ? true : false,
								'required' => true,
							));
bootcamp_register_step(	array(	'name'=> 'channel',
								'title'=> elgg_echo('bootcamp:step:channel:title'),
								'content'=> elgg_echo('bootcamp:step:channel:content'),
								'href'=> $user->getURL(),
								'completed' => $user->background_colour ? true : false,
								'priority' => 2,
								'required' => true,
							));
bootcamp_register_step(	array(	'name'=> 'subscribe',
								'title'=> elgg_echo('bootcamp:step:subscribe:title'),
								'content'=> elgg_echo('bootcamp:step:subscribe:content'),
								'href' => elgg_get_site_url() . 'channels',
								'priority' => 3,
								'completed' => $user->getFriends() ? true : false,
								'required' => true,
							));
function bootcamp_has_uploaded_media($user){
	$return = false;
	
	$options = array( 'types'=>'object', 'subtypes'=>array('kaltura_video', 'image', 'album', 'file'), 'owner_guid'=>$user->getGUID());
	$media = elgg_get_entities($options);
	if($media){
		$return = true;
	}
	return $return;
}
bootcamp_register_step(	array(	'name'=> 'upload',
								'title'=> elgg_echo('bootcamp:step:upload:title'),
								'content'=> elgg_echo('bootcamp:step:upload:content'),
								'href' => elgg_get_site_url() . 'archive/uploader',
								'priority' => 3,
								'completed' => bootcamp_has_uploaded_media($user),
								'required' => true,
							));
