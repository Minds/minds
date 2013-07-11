<?php
/**
 * Chat helper functions
 *
 * @package Chat
 */

/**
 * Get page components to list all chats the user is participating.
 *
 * @return array
 */
function chat_all ($container_guid = null) {
	elgg_register_title_button();
	
	$user_guid = elgg_get_logged_in_user_guid();
	
	if ($container_guid == $user_guid) {
		// Chats started by the user
		$params['filter_context'] = 'mine';
		
		$chats = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'chat',
			'limit' => 10,
			'pagination' => true,
			'full_view' => false,
			'container_guid' => $user_guid,
		));
	} else {
		// All chats that the user is participating
		$chats = elgg_list_entities_from_relationship(array(
			'type' => 'object',
			'subtype' => 'chat',
			'relationship' => 'member',
			'relationship_guid' => $user_guid,
			'inverse_relationship' => false,
			'limit' => 10,
			'pagination' => true,
			'full_view' => false,
		));
	}

	if (empty($chats)) {
		$chats = elgg_echo('chat:none');
	}

	$params['title'] = elgg_echo('chat');
	$params['content'] = $chats;

	return $params;
}

/**
 * List friends' chats that user is member of.
 * 
 * @param int $user_guid GUID of the user
 * @return array
 */
function chat_friends ($user_guid) {
	$user = get_user($user_guid);
	if (!$user) {
		forward('chat/all');
	}

	$params = array();
	$params['filter_context'] = 'friends';
	$params['title'] = elgg_echo('chat:title:friends');

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "chat/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('friends'));

	elgg_register_title_button();

	$options = array(
		'type' => 'object',
		'subtype' => 'chat',
		'relationship' => 'member',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'limit' => 10,
		'pagination' => true,
		'full_view' => false,
	);

	if ($friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
		foreach ($friends as $friend) {
			$options['container_guids'][] = $friend->getGUID();
		}
		$params['content'] = elgg_list_entities_from_relationship($options);
	}

	if (empty($params['content'])) {
		$params['content'] = elgg_echo('chat:none');
	}

	return $params;
}

/**
 * Get page components to edit/create a chat.
 *
 * @param int     $guid     GUID of chat
 * @return array
 */
function chat_edit ($guid = null) {
	if ($guid) {
		$chat = get_entity($guid);
		$form_vars = chat_prepare_form_vars($chat);
		$params['title'] = elgg_echo('chat:edit');
	} else {
		$form_vars = chat_prepare_form_vars();
		$params['title'] = elgg_echo('chat:add');
	}
	
	$form = elgg_view_form('chat/save', $vars, $form_vars);
	$params['content'] = $form;
	$params['filter'] = '';
	
	return $params;
}

/**
 * Get page components to view a chat.
 *
 * @param int $guid GUID of a chat entity.
 * @return array
 */
function chat_view($guid = NULL) {
	$return = array();

	elgg_load_js('elgg.chat_messaging');

	$chat = get_entity($guid);

	// no header or tabs for viewing an individual chat
	$return['filter'] = '';

	if (!elgg_instanceof($chat, 'object', 'chat') || !$chat->isMember() ) {
		$return['content'] = elgg_echo('noaccess');
		return $return;
	}

	if ($chat->canEdit()) {
		// Delete chat button
		elgg_register_menu_item('title', array(
			'name' => 'chat_delete',
			'href' => "action/chat/delete?guid=$guid",
			'text' => elgg_echo('delete'),
			'link_class' => 'elgg-button elgg-button-delete',
			'confirm' => elgg_echo('chat:delete:confirm'),
			'is_action' => true,
		));
		// Edit chat button
		elgg_register_menu_item('title', array(
			'name' => 'chat_edit',
			'href' => "chat/edit/$guid",
			'text' => elgg_echo('chat:edit'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	} else {
		// Leave chat button
		elgg_register_menu_item('title', array(
			'name' => 'chat_leave',
			'href' => "action/chat/leave?guid=$guid",
			'text' => elgg_echo('chat:leave'),
			'link_class' => 'elgg-button elgg-button-delete',
			'confirm' => elgg_echo('chat:leave:confirm'),
			'is_action' => true,
		));
	}
	// Add users button
	chat_register_addusers_button($chat);

	$return['title'] = htmlspecialchars($chat->title);

	elgg_push_breadcrumb($chat->title);
	$return['content'] = elgg_view_entity($chat, array('full_view' => true));
	$return['content'] .= elgg_view('chat/messages', array('entity' => $chat));

	return $return;
}

/**
 * View members of the chat as a form with an userpicker.
 * 
 * @param int $guid GUID of a chat entity.
 */
function chat_add_members($guid = null) {
	$chat = get_entity($guid);
	
	if (!elgg_instanceof($chat, 'object', 'chat') || !$chat->isMember() ) {
		$return['content'] = elgg_echo('noaccess');
		return $return;
	}
	
	$form_vars = array('guid' => $guid);
	$body_vars = array();
	$form = elgg_view_form('chat/addmembers', $body_vars, $form_vars);
	
	$return = array(
		'title' => elgg_echo('chat:members'),
		'content' => $form,
		'filter' => '',
	);
	
	return $return;
}

/**
 * Pull together chat variables for the save form
 *
 * @param ElggChat $chat
 * @return array
 */
function chat_prepare_form_vars($chat = NULL) {
	$user = elgg_get_logged_in_user_entity();

	// input names => defaults
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'access_id' => ACCESS_LOGGED_IN,
		'container_guid' => NULL,
		'guid' => NULL,
		'members' => get_input('members', NULL),
	);

	if ($chat) {
		foreach (array_keys($values) as $field) {
			if (isset($chat->$field)) {
				$values[$field] = $chat->$field;
			}
		}
		$values['members'] = $chat->getMemberGuids();
	}

	if (elgg_is_sticky_form('chat')) {
		$sticky_values = elgg_get_sticky_values('chat');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
		
		// This is not a property of chat so add separately
		$values['description'] = $sticky_values['message'];
	}
	
	elgg_clear_sticky_form('chat');

	return $values;
}

/**
 * Pull together chat message variables for the save form
 *
 * @param ElggObject $message chat_message object
 * @return array
 */
function chat_prepare_message_form_vars($message = NULL) {
	// input names => defaults
	$values = array(
		'description' => NULL,
		'access_id' => ACCESS_LOGGED_IN,
		'container_guid' => NULL,
		'guid' => NULL,
	);

	if ($message) {
		foreach (array_keys($values) as $field) {
			if (isset($message->$field)) {
				$values[$field] = $message->$field;
			}
		}
	}

	if (elgg_is_sticky_form('chat_message')) {
		$sticky_values = elgg_get_sticky_values('chat_message');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}
	
	elgg_clear_sticky_form('chat_message');

	return $values;
}

/**
 * Function to cache live chat sessions in sessions cookies
 * Move this over to a class to be cleaner
 */
function minds_live_chat_cache(){
	$cookie = $_COOKIE['minds_chats'];
	if(!isset($cookie)){
		return false;
	} else {
		return unserialize($cookie);
	}
}

function minds_live_chat_cache_add($id, $data){
	$cookie = $_COOKIE['minds_chats'];
        
	$cookie[$id] = $data;
	
	setcookie('minds_chats', serialize($cookie));

}
