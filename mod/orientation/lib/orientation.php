<?php

/**
 * Register a step that will show on the orientation page
 */
function orientation_register_step($params = array()){
	global $ORIENTATION;
	
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

	$ORIENTATION[$params['name']] = $info;

	return true;
}

/**
 * Return a list of all steps that have been registered
 * 
 * @todo is this function really needed? /MH
 */
function orientation_get_steps(){
	global $ORIENTATION;
	
	return $ORIENTATION;
}

/** 
 * Return a specific step
 */
function orientation_get_step($name){
	
}

/**
 * Calculate how many steps a user has completed
 */
function orientation_calculate_progress(){
	$steps = orientation_get_steps();
	
	$completed = 0;
	foreach($steps as $step){
		if($step->completed){
			$completed++;
		} 
	}
	$percentage = ($completed /  count($steps)) *100;
	return round($percentage);
}

/*
 * REGISTER THE STEPS WE WANT TO USE @todo MAKE THIS AVAILABLE IN PLUGIN SETTINGS
 */
$user = elgg_get_logged_in_user_entity();
orientation_register_step(	array(	'name'=> 'avatar',
								'title'=> elgg_echo('orientation:step:avatar:title'),
								'content'=> elgg_echo('orientation:step:avatar:content'),
								'href'=> elgg_get_site_url() . 'avatar/edit/'.$user->username,
								'priority' => 1,
								'completed' => $user->icontime ? true : false,
								'required' => true,
							));
orientation_register_step(	array(	'name'=> 'channel',
								'title'=> elgg_echo('orientation:step:channel:title'),
								'content'=> elgg_echo('orientation:step:channel:content'),
								'href'=> $user->getURL(),
								'completed' => $user->background_colour ? true : false,
								'priority' => 2,
								'required' => true,
							));
orientation_register_step(	array(	'name'=> 'subscribe',
								'title'=> elgg_echo('orientation:step:subscribe:title'),
								'content'=> elgg_echo('orientation:step:subscribe:content'),
								'href' => elgg_get_site_url() . 'channels',
								'priority' => 3,
								'completed' => $user->getFriends() ? true : false,
								'required' => true,
							));
							
function orientation_has_uploaded_media($user){
	$return = false;
	
	$options = array( 'types'=>'object', 'subtypes'=>array('kaltura_video', 'image', 'album', 'file'), 'owner_guid'=>$user->getGUID());
	$media = elgg_get_entities($options);
	if($media){
		$return = true;
	}
	return $return;
}
orientation_register_step(	array(	'name'=> 'upload',
								'title'=> elgg_echo('orientation:step:upload:title'),
								'content'=> elgg_echo('orientation:step:upload:content'),
								'href' => elgg_get_site_url() . 'archive/upload',
								'priority' => 4,
								'completed' => orientation_has_uploaded_media($user),
								'required' => true,
							));
function orientation_has_wallpost($user){
	$return = false;
	
	$options = array( 'types'=>'object', 'subtypes'=>array('wallpost'), 'owner_guid'=>$user->getGUID());
	$posts = elgg_get_entities($options);
	if($posts){
		$return = true;
	}
	return $return;
}
orientation_register_step(	array(	'name'=> 'wallpost',
								'title'=> elgg_echo('orientation:step:wallpost:title'),
								'content'=> elgg_echo('orientation:step:wallpost:content'),
								'href' => elgg_get_site_url() . 'news',
								'priority' => 5,
								'completed' => orientation_has_wallpost($user),
								'required' => true,
							));
orientation_register_step(	array(	'name'=> 'invite',
								'title'=> elgg_echo('orientation:step:invite:title'),
								'content'=> elgg_echo('orientation:step:invite:content'),
								'href' => elgg_get_site_url() . 'invite',
								'priority' => 6,
								'completed' => $user->hasInvited,
								'required' => true,
							));
orientation_register_step(	array(	'name'=> 'blog',
								'title'=> elgg_echo('orientation:step:blog:title'),
								'content'=> elgg_echo('orientation:step:blog:content'),
								'href'=> elgg_get_site_url() . 'blog/add',
								'priority' => 7,
								'completed' => elgg_get_entity( array(
																		'type'=> 'object',
																		'subtype'=>'blog',
																	 	'owner_guid'=>elgg_get_logged_in_user_guid(),
																							  )) ? true : false,
								'required' => true,
							));
