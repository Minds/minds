<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service for thumb up action
 *
 * @param int $guid 	   the guid of the object
 * @param string $username the username
 *
 * @return bool
 */
function thumb_up($guid, $username) {
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}

	//check to see if the user has already liked the item
	if (elgg_annotation_exists($guid, 'thumbs:up')) {
		$options = array('annotation_names'=> array('thumbs:up'), 'annotation_owner_guids'=> array(elgg_get_logged_in_user_guid()));
		$delete = elgg_delete_annotations($options);
		
		register_error(elgg_echo("thumbs:alreadyvotedup"));
		
		return false;
		
	} else {
	
	//delete a down vote if one exists
	if (elgg_annotation_exists($entity_guid, 'thumbs:down')) {
		$options = array('annotation_names'=> array('thumbs:down'), 'annotation_owner_guids'=> array(elgg_get_logged_in_user_guid()));
		elgg_delete_annotations($options);
	}

	// Let's see if we can get an entity with the specified GUID
	$entity = get_entity($guid);
	if (!$entity) {
		register_error(elgg_echo("thumbs:notfound"));
		return false;
	}
	
	// limit likes through a plugin hook (to prevent liking your own content for example)
	if (!$entity->canAnnotate(0, 'thumbs:up')) {
		// plugins should register the error message to explain why liking isn't allowed
		//forward(REFERER);
	}
	
	$annotation = create_annotation($entity->guid,
									'thumbs:up',
									1,
									"",
									elgg_get_logged_in_user_guid(),
									$entity->access_id);
	
	// tell user annotation didn't work if that is the case
	if (!$annotation) {
		register_error(elgg_echo("thumbs:failure"));
		return false;
	}
	
	echo elgg_view_icon('thumbs-up-alt');
	
	notification_create(array($entity->getOwnerGUID()), elgg_get_logged_in_user_guid(), $entity->guid, array('notification_view'=>'like'));
	
	}
} 
				
expose_function('thumb.up',
				"thumb_up",
				array(
						'guid' => array ('type' => 'int'),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"Thumb up a post",
				'POST',
				true,
				true);
