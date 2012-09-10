<?php

elgg_register_event_handler('init','system','polls_init');

function polls_init() {

	elgg_register_library('elgg:polls', elgg_get_plugins_path() . 'polls/models/model.php');

	// Set up menu for logged in users
	if (elgg_is_logged_in()) {
		$item = new ElggMenuItem('polls', elgg_echo('polls'), 'polls/owner/' . elgg_get_logged_in_user_entity()->username);
	} else {
		$item = new ElggMenuItem('polls', elgg_echo('poll'), 'polls/all');
	}
	elgg_register_menu_item('site', $item);

	// Extend system CSS with our own styles, which are defined in the polls/css view
	elgg_extend_view('css/elgg','polls/css');

	// Extend hover-over menu
	elgg_extend_view('profile/menu/links','polls/menu');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('polls','polls_page_handler');

	// Register a URL handler for poll posts
	elgg_register_entity_url_handler('object','poll','polls_url');
	
	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'polls_owner_block_menu');

	// Register entity type
	elgg_register_entity_type('object','poll');
	
	// register the JavaScript
	$js = elgg_get_simplecache_url('js', 'polls/js');
	elgg_register_simplecache_view('js/polls/js');
	elgg_register_js('elgg.polls', $js);

	// add group widget
	$group_polls = elgg_get_plugin_setting('group_polls', 'polls');
	if (!$group_polls || $group_polls != 'no') {
		elgg_extend_view('groups/tool_latest', 'polls/group_module');
	}

	if (!$group_polls || ($group_polls == 'yes_default')) {
		add_group_tool_option('polls',elgg_echo('polls:enable_polls'),TRUE);
	} else if ($group_polls == 'yes_not_default') {
		add_group_tool_option('polls',elgg_echo('polls:enable_polls'),FALSE);
	}

	//add widgets
	elgg_register_widget_type('poll',elgg_echo('polls:my_widget_title'),elgg_echo('polls:my_widget_description'));
	elgg_register_widget_type('latestPolls',elgg_echo('polls:latest_widget_title'),elgg_echo('polls:latest_widget_description'));
	elgg_register_widget_type('poll_individual',elgg_echo('polls:individual'),elgg_echo('poll_individual_group:widget:description'));	
	
	// Register actions
	$action_path = elgg_get_plugins_path() . 'polls/actions/polls';
	elgg_register_action("polls/add","$action_path/add.php");
	elgg_register_action("polls/edit","$action_path/edit.php");
	elgg_register_action("polls/delete","$action_path/delete.php");
	elgg_register_action("polls/vote","$action_path/vote.php");

}


/**
 * poll page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function polls_page_handler($page) {
	elgg_load_library('elgg:polls');
	$page_type = $page[0];
	switch($page_type) {
		case "view":		
			echo polls_get_page_view($page[1]);
			break;
		case "all":			
			echo polls_get_page_list($page_type);
			break;
		case "add":			
		case "edit":
			$container = null;
			if(isset($page[1])){
				$container = $page[1];
			}
			echo polls_get_page_edit($page_type,$container);
			break;
		case "friends":
		case "owner":
			$username = $page[1];
			$user = get_user_by_username($username);
			$user_guid = $user->guid;
			echo polls_get_page_list($page_type,$user_guid);
			break;
		case "group":
			echo polls_get_page_list($page_type,$page[1]);
			break;
		default:
			return FALSE;
			break;
	}
	return TRUE;
}

/**
 * Populates the ->getUrl() method for poll objects
 *
 * @param ElggEntity $pollpost poll post entity
 * @return string poll post URL
 */
function polls_url($poll) {
	$title = elgg_get_friendly_title($poll->title);
	return  "polls/view/" . $poll->guid . "/" . $title;
}

/**
 * Add a menu item to an owner block
 */
function polls_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "polls/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('polls', elgg_echo('polls'), $url);
		$return[] = $item;
	} else {
		elgg_load_library('elgg:polls');
		if (polls_activated_for_group($params['entity'])) {
			$url = "polls/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('polls', elgg_echo('polls:group_polls'), $url);
			$return[] = $item;
		}
	}

	return $return;
}
