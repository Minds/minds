<?php
/**
 * Vote up
 *
 * NOTES: THIS ACTION EITHER: a) adds a thumbs up value of 1 to the object
 *							  b) removes a thumbs down value
 *							  c) if a thumbs up vote is already there, deleteit.
 *
 */

$entity_guid = get_input('guid');
$id = get_input('id');
$type = get_input('type', 'entity');
$user_guid = elgg_get_logged_in_user_guid();

//user setting for orientation
elgg_set_plugin_user_setting('thumbs', true, elgg_get_logged_in_user_guid(), 'thumbs');

if ($type == 'entity') {

	// Let's see if we can get an entity with the specified GUID
	$entity = get_entity($entity_guid, 'object');
	if (!$entity) {
		register_error(elgg_echo("thumbs:notfound"));
		//forward(REFERER);
	}
	
	$thumbs_up = unserialize($entity->{'thumbs:up'});
	if(!is_array($thumbs_up)){ $thumbs_up = array(); }

	//check to see if the user has already liked the item
	if (in_array($user_guid, $thumbs_up)) {
		$options = array('annotation_names' => array('thumbs:up'), 'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()));
		$delete = elgg_delete_annotations($options);
		echo 'not selected';
		$entity -> thumbcount--;
		if(($key = array_search($user_guid, $thumbs_up)) !== false) {
 			unset($thumbs_up[$key]);
		}
		$entity->{'thumbs:up'} = serialize($thumbs_up);
		$entity->save();
	} else {

		if (elgg_annotation_exists($entity_guid, 'thumbs:down')) {
			$options = array('annotation_names' => array('thumbs:down'), 'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()));
			elgg_delete_annotations($options);

		}

		// limit likes through a plugin hook (to prevent liking your own content for example)
		if (!$entity -> canAnnotate(0, 'thumbs:up')) {
			// plugins should register the error message to explain why liking isn't allowed
			//forward(REFERER);
		}

		$entity -> thumbcount++;
	
                array_push($thumbs_up, $user_guid);
		$entity->{'thumbs:up'} = serialize($thumbs_up);

		//lets still create an annotation, be we are denormalising for speed
		$annotation = create_annotation($entity -> guid, 'thumbs:up', 1, "", elgg_get_logged_in_user_guid(), $entity -> access_id);
		$entity -> save();
		// tell user annotation didn't work if that is the case
		if (!$annotation) {
			register_error(elgg_echo("thumbs:failure"));
			forward(REFERER);
		}

		echo 'selected';

		notification_create(array($entity -> getOwnerGUID()), elgg_get_logged_in_user_guid(), $entity -> guid, array('notification_view' => 'like'));

	}
} elseif ($type == 'comment') {
	$comment_type = get_input('comment_type');
	//this is probably a little strange but we need to get the comment type eg if it is from a river or an entity.
	$mc = new MindsComments();
	$comment = $mc -> single($comment_type, $id);
	$thumbs = $comment['_source']['thumbs'];
	$user_guid = elgg_get_logged_in_user_guid();
	if (in_array($user_guid, $thumbs['up'])) {
		//there is a thumbs up for this user so we are going to remove it
		$comment['_source']['thumbs']['up'] = array_diff($comment['_source']['thumbs']['up'], array($user_guid));
		$icon = 'not-selected';
	} else {
		if (!is_array($comment['_source']['thumbs']['up'])) {
			$comment['_source']['thumbs']['up'] = array();
		}
		array_push($comment['_source']['thumbs']['up'], $user_guid);
		$icon = 'selected';
	}
	$update = $mc -> update($comment['_type'], $comment['_id'], $comment['_source']);
	if ($update['ok'] == true) {
		echo $icon;
		notification_create(array($comment['_source']['owner_guid']), elgg_get_logged_in_user_guid(), $comment['_source']['pid'], array('notification_view' => 'like', 'type'=>$type, 'description'=>$comment['_source']['description']));
	}
}

// Forward back to the page where the user 'liked' the object
//forward(REFERER);
