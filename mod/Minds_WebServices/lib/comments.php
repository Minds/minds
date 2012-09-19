<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
/**
 * Retrives a list of comments for an entity
 */ 
function comments_get($guid, $river_id){
	 	
	if(!$guid && !$river_id){
		return false;
	}	
	
	 $options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        'owner_guid' => null,
        //'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => 'generic_comment'),
            array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
            array('name' => 'parent_guid', 'value' => $guid),
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'count' => false,
        'limit' => 0,
        'order_by' => 'e.time_created desc'
    );
	
	$comments = elgg_get_entities_from_metadata($options);
	
	foreach($comments as $single){
		$comment['guid'] = $single->guid;
		$comment['comment'] = $single->annotation_value;
		
		//owner
		$owner = get_entity($single->owner_guid);
		$comment['owner']['guid'] = $owner->guid;
		$comment['owner']['name'] = $owner->name;
		$comment['owner']['username'] = $owner->username;
		$comment['owner']['avatar_url'] = $owner->getIconURL();
		
		$comment['time_created'] = $single->time_created;

		$return[] = $comment;
	}
	
	return $return;
	
} 
				
expose_function('comments.get',
				"comments_get",
				array(
						'guid' => array ('type' => 'int', 'required' => false),
						'river_id' => array ('type' => 'int', 'required' => false),
					),
				"Get a list of comments",
				'GET',
				true,
				true);
				
/**
 * Posts a comment
 */
function comments_post($comment,$guid, $river_id){
	if(!$guid && !$river_id){
		return false;
	}	
 	$annotation = new hjAnnotation();
	$annotation->annotation_value = $comment;
	$annotation->annotation_name = 'generic_comment';
	$annotation->title = '';
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	//set it as metadata and then there is no problem
	$annotation->parent_guid = $guid;
	$annotation->river_id = $river_id;
	$annotation->access_id = ACCESS_DEFAULT;
	$guid = $annotation->save();
	
	if($guid){
		return true;
	} else {
		return false;
	}
}

expose_function('comments.post',
				"comments_post",
				array(
						'comment' => array ('type' => 'string'),
						'guid' => array ('type' => 'int', 'required' => false),
						'river_id' => array ('type' => 'int', 'required' => false),
					),
				"Make a comment",
				'POST',
				true,
				true);