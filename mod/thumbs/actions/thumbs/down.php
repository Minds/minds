<?php
/**
 * Vote down
 *
 * NOTES: THIS ACTION EITHER: a) adds a thumbs down value of 1 to the object
 *							  b) removes a thumbs up value
 *							  c) if a thumbs down vote is already there, deleteit.
 *
 */

$entity_guid = (int) get_input('guid');

//check to see if the user has already liked the item
if (elgg_annotation_exists($entity_guid, 'thumbs:down')) {
	$options = array('annotation_names'=> array('thumbs:down'), 'annotation_owner_guids'=> array(elgg_get_logged_in_user_guid()));
	$delete = elgg_delete_annotations($options);
	//if($delete){
		echo elgg_view_icon('thumbs-down');
	//}
	
} else {

	if (elgg_annotation_exists($entity_guid, 'thumbs:up')) {
		$options = array('annotation_names'=> array('thumbs:up'), 'annotation_owner_guids'=> array(elgg_get_logged_in_user_guid()));
		elgg_delete_annotations($options);
		
	} 
	// Let's see if we can get an entity with the specified GUID
	$entity = get_entity($entity_guid);
	if (!$entity) {
		register_error(elgg_echo("thumbs:notfound"));
		forward(REFERER);
	}
	
	// limit likes through a plugin hook (to prevent liking your own content for example)
	if (!$entity->canAnnotate(0, 'thumbs:up')) {
		// plugins should register the error message to explain why liking isn't allowed
		forward(REFERER);
	}
	
	$annotation = create_annotation($entity->guid,
									'thumbs:down',
									1,
									"",
									elgg_get_logged_in_user_guid(),
									$entity->access_id);
	
	// tell user annotation didn't work if that is the case
	if (!$annotation) {
		register_error(elgg_echo("thumbs:failure"));
		forward(REFERER);
	}
	
	
	echo elgg_view_icon('thumbs-down-alt');
	
}

// Forward back to the page where the user 'liked' the object
//forward(REFERER);
