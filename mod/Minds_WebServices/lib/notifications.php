<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service for getting the count of notifications
 *
 * @return bool
 */	
expose_function('notifications.count',
				"notifications_count_unread",
				array(
					
					),
				"Get the number of unread notifications",
				'GET',
				true,
				true);
				
/**
 * Web service for retrieving notifications
 *
 * @param int $limit
 * @param int $offset
 *
 * @return bool
 */
function notification_get_posts($limit = 10, $offset = 0) {
	
	$user = elgg_get_logged_in_user_entity();
	
	$options = array(	'types'=>'object',
					'subtypes'=>'notification',
					'metadata_name_value_pairs' => array(array('name'=>'to_guid', 'value'=>$user->getGUID(), 'operand' => '='))
				);
	
	$notifications = elgg_get_entities_from_metadata($options);
	
	if($notifications){
		foreach($notifications as $single ) {
			$notification['guid'] = $single->guid;
			
			//subject
			$actor = get_entity($single->from_guid);
			$notification['actor']['guid'] = $actor->guid;
			$notification['actor']['name'] = $actor->name;
			$notification['actor']['username'] = $actor->name;
			$notification['actor']['avatar_url'] = get_entity_icon_url($actor,'small');
			
			//object
			$object = get_entity($single->object_guid);
			$notification['object']['guid'] = $object->guid;
			$notification['object']['name'] = $object->name ? $object->name : $object->title;
			$notification['object']['description'] = $object->description;
				
			$notification['time_created'] = (int)$single->time_created;
			$notification['description'] = $single->description;
			$notification['view'] = $entity->notification_view;
			
			$read = $single->read;
			
			if ($read != 1) {
				// Mark message read
				$single->read = 1;
				$single->save();
			}
			
			$notification['read'] = $single->read;
			$return[] = $notification;
		} 
	} else {
			$msg = elgg_echo('notifications:none');
			throw new InvalidParameterException($msg);
		}
	
	return $return;
} 
				
expose_function('notifications.get',
				"notification_get_posts",
				array(	
						'limit' => array ('type' => 'int', 'required' => false),
						'offset' => array ('type' => 'int', 'required' => false),
					),
				"Read lates wire post",
				'GET',
				false,
				false);
				
