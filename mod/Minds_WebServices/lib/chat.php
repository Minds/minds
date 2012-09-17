<?php
/**
 * Minds Web Services
 * Events
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */

  /**
 * Web service to get the count of unread message
 *
 * @return int
 */
expose_function('chats.count',
				"chat_count_unread_messages",
				array(
					),
				"Get undread chat messages count",
				'GET',
				false,
				false);

 /**
  * Web service to get a list of chats the user in involved with
  * 
  * @return params  
  */
 function chats_get(){
 	$user_guid = elgg_get_logged_in_user_guid();
	
	
	$chats = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'subtype' => 'chat',
				'relationship' => 'member',
				'relationship_guid' => $user_guid,
				'inverse_relationship' => false,
				'limit' => 10,
				'pagination' => true,
				'full_view' => false,
		));
	
		
	if($chats){
			foreach($chats as $single) {
				$chat['guid'] = $single->guid;
				
				$chat['title'] = $single->title;
				
				//members
				$members = $single->getMemberEntities();
				foreach ($members as $member) {
					$chat['members'][] = $member->name;
				}
				
				$chat['time_created'] = (int)$single->time_created;
				
				$return[] = $chat;
			}
	} else {
			$return = elgg_echo('chat:none');
	}
	
	return $return;
 }
expose_function('chats.get',
				"chats_get",
				array(
					),
				"Get  a list of message",
				'GET',
				true,
				true);
 /**
  * Web service to get a chat thread
  * 
  * @return return
  */
function chat_get($guid){
	$messages = elgg_get_entities(array(
										'type' => 'object',
										'subtype' => 'chat_message', 
										//'container_guid' => $guid,
										'limit' => 10,
										'order_by' => 'e.time_created desc',
										'pagination' => false,
									));
	$messages = array_reverse($messages);
		
	if($messages){
			foreach($messages as $single) {
				$message['guid'] = $single->guid;
				
				//owner
				$owner = get_entity($single->owner_guid);
				$message['owner']['guid'] = $owner->guid;
				$message['owner']['name'] = $owner->name;
				$message['owner']['avatar_url'] = $owner->getIconURL('small');
				
				$message['description'] = strip_tags($single->description);
				
				$return[] = $message;
			}
	}
	
	return $return;
	
}
expose_function('chat.get',
				"chat_get",
				array(	'guid' => array ('type' => 'int'),
					),
				"Get  a list of message",
				'GET',
				true,
				true);
