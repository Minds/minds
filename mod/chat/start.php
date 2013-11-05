<?php
/**
 * Chat
 *
 * @package Chat
 */
 
/**
 * Initialize the plugin.
 */
function chat_init() {
	global $CONFIG;

	if(elgg_get_viewtype() != 'default'){
		return;
	}
	
	$actionspath = $CONFIG->pluginspath . "chat/actions/chat";
	elgg_register_action("chat/save", "$actionspath/save.php");
	elgg_register_action("chat/addmembers", "$actionspath/addmembers.php");
	elgg_register_action("chat/leave", "$actionspath/leave.php");
	elgg_register_action("chat/delete", "$actionspath/delete.php");
	elgg_register_action("chat/message/save", "$actionspath/message/save.php");
	elgg_register_action("chat/message/delete", "$actionspath/message/delete.php");

//	$libpath = elgg_get_plugins_path() . 'chat/lib/chat.php';
//	elgg_register_library('chat', $libpath);

	// Register the chat's JavaScript
//	$chat_js = elgg_get_simplecache_url('js', 'chat/chat');
//	elgg_register_simplecache_view('js/chat/chat');
//	elgg_register_js('elgg.chat', $chat_js);
//	elgg_load_js('elgg.chat');

	// Register the chat's messaging JavaScript
//	$chat_messaging_js = elgg_get_simplecache_url('js', 'chat/messaging');
//	elgg_register_simplecache_view('js/chat/messaging');
//	elgg_register_js('elgg.chat_messaging', $chat_messaging_js);


	elgg_register_js('portal', elgg_get_site_url() . 'mod/chat/vendors/portal.js');
	elgg_load_js('portal');
	elgg_register_js('atmosphere', elgg_get_site_url() . 'mod/chat/vendors/atmosphere.js');
	elgg_load_js('atmosphere');

	$minds_live = elgg_get_simplecache_url('js', 'chat/live');
	elgg_register_js('minds.live', $minds_live);
	elgg_load_js('minds.live');

	if(elgg_is_logged_in() && elgg_get_context() != 'admin'){
		elgg_extend_view('page/elements/foot', 'chat/bar');
	}

	// Add custom CSS
	elgg_extend_view('css', 'chat/css');

	// Hook to customize user hover menu
//	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'chat_user_hover_menu');
//	elgg_register_plugin_hook_handler('register', 'menu:entity', 'chat_entity_menu_setup');
//	elgg_register_plugin_hook_handler('register', 'menu:entity', 'chat_message_menu_setup');
//	elgg_register_plugin_hook_handler('permissions_check', 'object', 'chat_permissions_override');

////	elgg_register_event_handler('pagesetup', 'system', 'chat_page_setup');
//	elgg_register_event_handler('pagesetup', 'system', 'chat_notifier');
	
	elgg_register_page_handler('chat', 'chat_page_handler');
	
	// override the default url to view a chat object
	elgg_register_entity_url_handler('object', 'chat', 'chat_url_handler');
}

/**
 * Dispatche chat pages.
 * 
 * @param array $page
 * @return bool
 */
function chat_page_handler ($page) {
//	elgg_load_library('chat');
	
	if (!isset($page[0])) {
		elgg_push_breadcrumb(elgg_echo('chat'));
		$page[0] = 'all';
	} else {
		elgg_push_breadcrumb(elgg_echo('chat'), 'chat/all');
	}

	switch ($page[0]) {
		case 'owner':
			$user = get_user_by_username($page[1]);
			$params = chat_all($user->guid);
			break;
		case 'friends':
			$user = get_user_by_username($page[1]);
			$params = chat_friends($user->guid);
			break;
		case 'add':
			gatekeeper();
			$params = chat_edit();
			break;
		case 'edit':
			gatekeeper();
			$params = chat_edit($page[1]);
			break;
		case 'view':
			$params = chat_view($page[1]);
			break;
		case 'members':
			gatekeeper();
			$params = chat_add_members($page[1]);
			break;
		case 'box':
			echo elgg_view('chat/box', array('user_guid'=>get_input('user_guid')));
			return true;
			break;
		case 'return_userlist':
			$guids = get_input('guids'); 
			$users = elgg_get_entities(array('type'=>'user','guids'=>$guids));
                        foreach($users as $user){
                        	if($user->guid != elgg_get_logged_in_user_guid()){
                                      echo "<li class='user' id='$user->guid'> <h3>$user->name</h3></li>";
                                }
                        }
			return true;
			break;
		case 'all':
		default:
			$params = chat_all();
			break;
		}
	
	$body = elgg_view_layout('content', $params);
	echo elgg_view_page('test', $body);
	return true;
}

/**
 * Format and return the URL for chats.
 *
 * @param ElggObject $entity Chat object
 * @return string URL of chat.
 */
function chat_url_handler($entity) {
	if (!$entity->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "chat/view/{$entity->guid}/$friendly_title";
}

/**
 * Remove possiblity to edit profile and avatar from facebook accounts.
 */
function chat_user_hover_menu ($hook, $type, $return, $params) {
	$user = $params['entity'];

	return $return;
}

/**
 * Add title button for adding more people to a chat.
 * 
 * All members of the chat are allowed to add people.
 * 
 * @todo Is it possible to use userpicker through lightbox?
 * 
 * @param obj $entity ElggChat object
 */
function chat_register_addusers_button($entity) {
	if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
			
		if ($user && $entity->isMember()) {
			$guid = $entity->getGUID();
			elgg_register_menu_item('title', array(
				'name' => 'chat_members',
				'href' => "chat/members/$guid",
				'text' => elgg_echo('chat:members:add'),
				'link_class' => 'elgg-button elgg-button-action', // elgg-lightbox
			));
		}
		
		/*
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		
		elgg_load_js('elgg.userpicker');
		*/
	}
}

/**
 * Set up the entity menu for chat entities.
 */
function chat_entity_menu_setup ($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'chat') {
		return $return;
	}

	$num_messages = $entity->getUnreadMessagesCount();
	if ($num_messages) {
		$text = elgg_echo('chat:unread_message', array($num_messages));
		$options = array(
			'name' => 'unread_mesages',
			'text' => $text,
			'href' => false,
			'priority' => 150,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	$remove = array('access', 'likes');
	if (elgg_in_context('chat_preview')) {
		$remove[] = 'edit';
		$remove[] = 'delete';
	}

	// Remove items from menu depending on situation
	foreach ($return as $index => $item) {
		if (in_array($item->getName(), $remove)) {
			unset($return[$index]);
		}
	}

	return $return;
}

/**
 * Set up the entity menu for chat messages.
 */
function chat_message_menu_setup ($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	
	if ($entity->getSubtype() !== 'chat_message') {
		return $return;
	}

	$remove = array('access');
	
	$user = elgg_get_logged_in_user_entity();

	if ($entity->getOwnerGUID() == $user->getGUID() || $user->isAdmin()) {
		$guid = $entity->getGUID();
		
		$options = array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "#chat-edit-message-$guid",
			'priority' => 100,
			'rel' => 'toggle',
		);
		$return[] = ElggMenuItem::factory($options);

		$options = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'href' => "action/chat/message/delete?guid=$guid",
			'priority' => 150,
			'is_action' => true,
		);
		$return[] = ElggMenuItem::factory($options);
	
	} else {
		$remove[] = 'edit';
		$remove[] = 'delete';
	}

	// Remove items from menu depending on situation
	foreach ($return as $index => $item) {
		if (in_array($item->getName(), $remove)) {
			unset($return[$index]);
		}
	}

	return $return;
}

/**
 * Display notification of new chat messages in topbar
 */
function chat_notifier() {
	if (elgg_is_logged_in()) {
		// Add hidden popup module to topbar
		elgg_extend_view('page/elements/topbar', 'chat/preview');
					  
		$class = "message notifier entypo";
		$text = "&#59168;";
		$tooltip = elgg_echo("chat:messages");
		// get unread messages
		$num_unread = (int)chat_count_unread_messages();
		if ($num_unread != 0) {
			$class = "message notifier new";
			$tooltip .= " (" . elgg_echo("notifications:unread", array($num_unread)) . ")";
		}

		// This link opens the popup module
		elgg_register_menu_item('notifications', array(
			'name' => 'chat-notifier',
			'href' => '#chat-messages-preview',
			'text' => $text,
			'priority' => 700,
			'title' => $tooltip,
			'class' => 'entypo',
			'rel' => 'popup',
			'id' => 'chat-preview-link',
			'section' => 'alt' //this is custom to minds theme.
		));
	}
}

/**
 * Get all chats with unread messages.
 * 
 * @param array $options See elgg_get_entities_from_annotations().
 */
function chat_get_unread_chats($options = array()) {
	$user = elgg_get_logged_in_user_entity();
	
	$defaults = array(
		'type' => 'object',
		'subtype' => 'chat',
		'annotation_names' => 'unread_messages',
		'annotation_owner_guids' => $user->getGUID(),
		'count' => false,
	);
	
	$options = array_merge($defaults, $options);
	
	return elgg_get_entities_from_annotations($options);
}

/**
 * Get the number of all unread chat messages.
 * 
 * @return mixed False on error, int if success.
 */
function chat_count_unread_messages() {
	$user = elgg_get_logged_in_user_entity();

	$chats = elgg_get_entities_from_annotations(array(
		'type' => 'object',
		'subtype' => 'chat',
		'annotation_name' => 'unread_messages',
		'annotation_owner_guids' => $user->getGUID(),
		'limit' => 5,
		/* @todo Ordering doesn't seem to work
		'order_by_annotation' => array(
			'name' => 'unread_messages',
			'direction' => 'desc',
			'as' => 'integer',
		),
		*/
	));
	
	$message_count = 0;
	$guids = array();
	if ($chats) {
		foreach ($chats as $chat) {
			$message_count += $chat->getUnreadMessagesCount();
			
		}
	}
	return $message_count;
}

/**
 * Allow chat members to add messages to chat.
 */
function chat_permissions_override ($hook, $type, $return, $params) {
	$entity = $params['entity'];

	// Allow full access to administrators
	if (elgg_is_admin_logged_in()) {
		return true;
	}

	// Allow chat members to add messages to chat
	if (elgg_instanceof($entity, 'object', 'chat')) {
		if ($entity->isMember() && elgg_in_context('chat_message')) {
			return true;
		}
	}

	return $return;
}

elgg_register_event_handler('init', 'system', 'chat_init');
