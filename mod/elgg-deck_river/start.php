<?php
/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ggouv/elgg-deck_river
 **/

elgg_register_event_handler('init','system','deck_river_init');

function deck_river_init() {

	$path = elgg_get_plugins_path() . 'elgg-deck_river/';
	elgg_register_library('deck_river:river_loader', $path . 'lib/river_loader.php');
	elgg_register_library('deck_river:api', $path . 'lib/api.php');
	elgg_register_library('deck_river:authorize', $path . 'lib/authorize.php');
	elgg_register_library('deck_river:twitter_async', $path . 'vendors/load_twitter_async.php');
	elgg_register_library('deck_river:facebook_sdk', $path . 'vendors/facebook-php-sdk/src/facebook.php');
	elgg_register_library('alphaGUID', $path . 'vendors/alphaID.inc.php');

	elgg_load_library('alphaGUID');

	elgg_extend_view('css/elgg','deck_river/css');
	elgg_extend_view('js/elgg', 'deck_river/js', 600);
	elgg_extend_view('page/elements/foot', 'deck_river/mustaches_wrapper', 499);
	elgg_extend_view('page/elements/foot', 'page/layouts/content/deck_river_add_new_tab', 500);

	elgg_register_ajax_view('deck_river/ajax_json/column_river');
	elgg_register_ajax_view('deck_river/ajax_json/entity_river');
	elgg_register_ajax_view('deck_river/ajax_json/entity_mention');
	elgg_register_ajax_view('deck_river/ajax_json/url_shortener');
	elgg_register_ajax_view('deck_river/ajax_json/load_discussion');
	elgg_register_ajax_view('deck_river/ajax_json/twitter_OAuth');
	elgg_register_ajax_view('deck_river/ajax_view/column_settings');
	elgg_register_ajax_view('deck_river/ajax_view/add_social_network');
	elgg_register_ajax_view('deck_river/ajax_view/user_info');
	elgg_register_ajax_view('deck_river/ajax_view/group_info');
	elgg_register_ajax_view('deck_river/ajax_view/share_account');

	// register page handlers
	elgg_register_page_handler('activity', 'deck_river_page_handler');
	elgg_register_page_handler('message', 'deck_river_wire_page_handler');
	elgg_register_page_handler('authorize', 'authorize_page_handler');
	elgg_register_page_handler('u', 'alphaGUID_page_handler');
	elgg_register_page_handler('bookmarklet', 'bookmarklet_handler');

	// register actions
	$action_path = elgg_get_plugins_path() . 'elgg-deck_river/actions';
	elgg_register_action('deck_river/add_message', "$action_path/message/add.php");
	elgg_register_action('message/delete', "$action_path/message/delete.php");
	elgg_register_action('deck_river/column/settings', "$action_path/column/settings.php");
	elgg_register_action('deck_river/column/move', "$action_path/column/move.php");
	elgg_register_action('deck_river/tab/add', "$action_path/tab/add.php");
	elgg_register_action('deck_river/tab/delete', "$action_path/tab/delete.php");
	elgg_register_action('deck_river/tab/rename', "$action_path/tab/rename.php");
	elgg_register_action('deck_river/network/manageNetworks', "$action_path/network/manage_network.php");
	elgg_register_action('deck_river/network/delete', "$action_path/network/delete_network.php");
	elgg_register_action('deck_river/network/share', "$action_path/network/share_network.php");
	elgg_register_action('elgg-deck_river/settings/save', "$action_path/plugins/save.php");

	elgg_register_action('deck_river/network/getLoginUrl', "$action_path/network/getLoginUrl.php");
	elgg_register_action('deck_river/twitter', "$action_path/network/twitter.php");
	elgg_register_action('deck_river/facebook', "$action_path/network/facebook.php");
	elgg_register_action('deck_river/add_facebook_group', "$action_path/network/add_facebook_group.php");
	elgg_register_action('deck_river/add_facebook_page', "$action_path/network/add_facebook_page.php");

	// Register a URL handler for thewire posts
	elgg_register_entity_url_handler('object', 'thewire', 'deck_river_thewire_url');

	// Register for search
	elgg_register_entity_type('object', 'thewire');

	// owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'deck_river_thewire_owner_block_menu');

	// add menu in usersettings page
	elgg_register_event_handler('pagesetup', 'system', 'authorize_applications_pagesetup');

	// unregistrer trigger for river menu
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'deck_river_menu_setup');

	// autofollow twitter account
	elgg_register_event_handler('authorize', 'deck_river:twitter', 'deck_river_autofollow_twitter_account');

	// register acces hook
	elgg_register_plugin_hook_handler('access:collections:write', 'user', 'deck_river_access_collections');

}


function deck_river_page_handler($page) {

	if (elgg_is_logged_in()) {

		// get user settings
		$user_guid = elgg_get_logged_in_user_guid();
		$user_river_settings = json_decode(get_private_setting($user_guid, 'deck_river_settings'), true);

		// if first time, create settings for this user
		if ( !$user_river_settings || !is_array($user_river_settings) ) {
			$set = str_replace("&gt;", ">", elgg_get_plugin_setting('default_columns', 'elgg-deck_river'));
			if (!$set) $set = elgg_echo('deck_river:settings:default_column:default');
			//eval("\$defaults = $set;");
			$user = get_entity($user_guid, 'user');
			$user->setPrivateSetting('deck_river_settings', json_encode($defaults));
			$user_river_settings = $defaults;
		}

		if (!isset($page[0])) {
			reset($user_river_settings);
			$page[0] = key($user_river_settings);
		}

		elgg_set_context($page[0]);
		include_once dirname(__FILE__) . '/pages/river.php';

	} else {
		forward('');
	}

	return true;
}

/**
 * The wire page handler
 *
 * Supports:
 * message/owner/<username>     View this user's wire posts
 * message/view/<guid>          View a post
 * thewire/tag/<tag>            View wire posts tagged with <tag>
 *
 * @param array $page From the page_handler function
 * @return bool
 */
function deck_river_wire_page_handler($page) {

	$base_dir = elgg_get_plugins_path() . 'elgg-deck_river/pages/thewire';

	if (!isset($page[0]) || $page[0] == 'all') {
		forward('activity');
	}

	switch ($page[0]) {
		case "owner":
			include "$base_dir/owner.php";
			break;
		case "view":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/view.php";
			break;
		case "tag":
			if (isset($page[1])) {
				set_input('tag', $page[1]);
			}
			include "$base_dir/tag.php";
			break;
		default:
			return false;
	}
	return true;
}



/**
 * Serves pages for social network authorization.
 *
 * @param array $page
 * @return void
 */
function authorize_page_handler($page) {
	if (!isset($page[0])) {
		return false;
	}

	elgg_load_library('deck_river:authorize');

	switch ($page[0]) {
		case 'twitter':
			deck_river_twitter_authorize();
			break;
		case 'facebook':
			deck_river_facebook_authorize();
			break;
		case 'applications':
			include elgg_get_plugins_path() . 'elgg-deck_river/pages/applications.php';
			break;
		default:
			return false;
	}
	return true;
}


/* Forward to the URL of an object from a alpha-minified url like http://domain/u/Zsi6
 *
 * @param Array $page With only one item which is a alphanumeric value corresponding to a entity GUID
 */
function alphaGUID_page_handler($page) {

	if (!isset($page[0])) forward('activity');

	$object_guid = alphaID($page[0], true);
	$object_entity = get_entity($object_guid);

	if (!$object_entity) forward('404');

	forward($object_entity->getURL());

}


/* bookmarklet */
function bookmarklet_handler($page) {

	switch ($page[0]) {
		default:
		case 'popup':
			require_once(elgg_get_plugins_path() . 'elgg-deck_river/pages/bookmarklet/popup.php');
			break;
		case 'install':
			elgg_push_breadcrumb(elgg_echo('bookmarklet'));
			include elgg_get_plugins_path() . 'elgg-deck_river/pages/bookmarklet/install.php';
			break;
	}

	return true;
}


/* add a menu in user settings page */
function authorize_applications_pagesetup() {
	if (elgg_get_context() == "settings") {
		$user = elgg_get_page_owner_entity();

		$params = array(
			'name' => 'applications',
			'text' => elgg_echo('usersettings:authorize:applications'),
			'href' => "authorize/applications/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
	}
}



/**
 * Override the url for a wire post to return the thread
 *
 * @param ElggObject $thewirepost Wire post object
 */
function deck_river_thewire_url($thewirepost) {
	return "message/view/" . $thewirepost->guid;
}



/**
 * Replace urls, hashtags,  ! and @ by popups
 *
 * @param string $text The text of a post
 * @return string
 */
function deck_river_wire_filter($text) {
	$text = ' ' . $text;

	// email addresses
	$text = preg_replace(
				'/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
				'$1<a href="mailto:$2@$3">$2@$3</a>',
				$text);

	// links
	$text = parse_urls($text);

	// usernames
	$text = preg_replace(
				'/(^|[^\w])@([\p{L}\p{Nd}_]+)/u',
				'$1<a class="info-popup elgg-user-info-popup" href="#" title="$2">@$2</a>',
				$text);

	// groups
	$text = preg_replace(
				'/(^|[^\w])!([\p{L}\p{Nd}_]+)/u',
				'$1<a class="info-popup group-info-popup" href="#" title="$2">!$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a class="info-popup hashtag-info-popup" href="#" title="#$2">#$2</a>',
				$text);

	$text = trim($text);

	return $text;
}


/**
 * Highlight mention in text
 * @param  [string] $text    text that contains mention
 * @param  [string] $mention mention
 * @return [string]          text with mention highlighted
 */
function deck_river_highlight_mention($text, $mention) {
	$len = mb_strlen($mention);
	$match = preg_split('/'.$mention.'/Umis', $text);

	if (count($match) >= 1) {
		$a = array_shift($match);
		$b = implode($mention, $match);

		if (mb_strlen($b) > 70-$len) {
			$a1 = mb_substr($a, -70);
			$b1 = mb_substr($b, 0, 140-$len-mb_strlen($a1));
			if (mb_strlen($b1) < mb_strlen($b)) $b1 = ' ' . trim($b1) . '...';
		} else {
			$b1 = $b;
			$a1 = mb_substr($a, -(140-$len-mb_strlen($b1)));
		}
		if (mb_strlen($a1) < mb_strlen($a)) $a1 = '...' . trim($a1) . ' ';
	} else {
		return $text;
	}

	return $a1 . '<span class="search-highlight">' . $mention . '</span>' . $b1;
}


/**
 * Replace urls, hashtags,  ! and @ by links
 *
 * @param string $text The text of a post
 * @return string
 */
function deck_river_wire_filter_external($text) {
	$text = ' ' . $text;

	// email addresses
	$text = preg_replace(
				'/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
				'$1<a href="mailto:$2@$3">$2@$3</a>',
				$text);

	// links
	$text = parse_urls($text);

	// usernames
	$text = preg_replace(
				'/(^|[^\w])@([\p{L}\p{Nd}_]+)/u',
				'$1<a href="'. elgg_get_site_url() .'profile/$2" title="$2">@$2</a>',
				$text);

	// groups
	$text = preg_replace(
				'/(^|[^\w])!([\p{L}\p{Nd}_]+)/u',
				'$1<a href="'. elgg_get_site_url() .'groups/profile/$2" title="$2">!$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a href="'. elgg_get_site_url() .'search?q=$2&search_type=tags" title="#$2">#$2</a>',
				$text);

	$text = trim($text);

	return $text;
}



/**
 * Get group by title
 *
 * @param string $group The title's group
 *
 * @return GUID|false Depending on success
 */
function search_group_by_title($group) {
	global $CONFIG, $GROUP_TITLE_TO_GUID_MAP_CACHE;

	$group = sanitise_string($group);

	// Caching
	if ((isset($GROUP_TITLE_TO_GUID_MAP_CACHE[$group]))
	&& (retrieve_cached_entity($GROUP_TITLE_TO_GUID_MAP_CACHE[$group]))) {
		return retrieve_cached_entity($GROUP_TITLE_TO_GUID_MAP_CACHE[$group])->guid;
	}

	$guid = get_data("SELECT guid from {$CONFIG->dbprefix}groups_entity where name='$group'");

	if ($guid) {
		$GROUP_TITLE_TO_GUID_MAP_CACHE[$group] = $guid[0]->guid;
	} else {
		$guid = false;
	}

	if ($guid) {
		return $guid[0]->guid;
	} else {
		return false;
	}
}



/**
 * Get an array of hashtags from a text string
 *
 * @param string $text The text of a post
 * @return array
 */
function deck_river_thewire_get_hashtags($text) {
	// beginning of text or white space followed by hashtag
	// hashtag must begin with @ and contain at least one alphanumeric character
	$matches = array();
	preg_match_all('/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/', $text, $matches);
	return $matches[2];
}



/**
 * Get an array of users from a text string
 *
 * @param string $text The text of a post
 * @return array
 */
function deck_river_thewire_get_users($text) {
	// beginning of text or white space followed by hashtag
	// hashtag must begin with # and contain at least one character not digit, space, or punctuation
	$matches = array();
	preg_match_all('/(^|[^\w])@([\p{L}\p{Nd}._]+)/u', $text, $matches);

	// check if users exists
	$users = array();
	foreach ($matches[2] as $key => $user) {
		$users[] = get_user_by_username($user);
	}
	return $users;
}



/**
 * Create a new wire post.
 *
 * @param array $params Array in format : 
 *              body           => string       The post text
 *              owner_guid     => int          The owner's guid of the post
 *              access_id      => string       Public/private etc
 *              parent_guid    => int          Parent post guid (if any)
 *              method         => string       The method (default: 'site')
 * @return guid or false if failure
 */
function deck_river_thewire_save_post(array $params) {
	$defaults = array(
		'access_id' => ACCESS_PUBLIC,
		'parent_guid' => 0,
		'method' => 'site'
	);
	$params = array_merge($defaults, $params);

	$post = new ElggObject();

	$post->subtype = "thewire";
	$post->owner_guid = $params['owner_guid'];
	$post->access_id = $params['access_id'];
	$post->description = $params['body'];
	$post->method = $params['method']; //method: site, bookmarklet, email, api, ...

	$tags = deck_river_thewire_get_hashtags($params['body']);
	if ($tags) {
		$post->tags = $tags;
	}

	// must do this before saving so notifications pick up that this is a reply
	if ($params['parent_guid']) {
		$post->reply = true;
	}

	$guid = $post->save();

	// set thread guid
	if ($params['parent_guid']) {
		$post->addRelationship($params['parent_guid'], 'parent');

		// name conversation threads by guid of first post (works even if first post deleted)
		$parent_post = get_entity($params['parent_guid']);
		$post->wire_thread = $parent_post->wire_thread;
	} else {
		// first post in this thread
		$post->wire_thread = $guid;
	}

	if ($guid) {
		add_to_river('river/object/thewire/create', 'create', $post->owner_guid, $post->guid);

		// let other plugins know we are setting a user status
		$params = array(
			'entity' => $post,
			'user' => $post->getOwnerEntity(),
			'message' => $post->description,
			'url' => $post->getURL(),
			'origin' => 'thewire',
		);
		elgg_trigger_plugin_hook('status', 'user', $params); // original elgg hook
	}

	return $guid;
}



/**
 * Returns the notification body
 *
 * @return $string
 */
function deck_river_thewire_notify_message($guid, $parent_guid) {
	$entity = get_entity($guid);
	$descr = deck_river_wire_filter_external($entity->description);
	$owner = $entity->getOwnerEntity();

	$parent_post = get_entity($parent_guid);

	$owner_url = elgg_view('output/url', array(
		'href' => $owner->getURL(),
		'text' => $owner->name,
		'is_trusted' => true,
	));
	$this_message = elgg_view('output/url', array(
		'href' => $entity->getURL(),
		'text' => elgg_echo('thewire:notify:thismessage'),
		'is_trusted' => true,
	));
	$your_message = elgg_view('output/url', array(
		'href' => $parent_post->getURL(),
		'text' => elgg_echo('thewire:notify:yourmessage'),
		'is_trusted' => true,
	));
	$body = elgg_echo('thewire:notify:reply', array($owner_url, $this_message));
	$body .= "\n\n" . '<div style="background-color: #FAFAFA;font-size: 1.4em;padding: 10px;">' . $descr . '</div>' . "\n";
	$body .= elgg_echo('thewire:notify:atyourmessage', array($your_message));
	$body .= "\n\n" . '<div style="background-color: #FAFAFA;font-size: 1.1em;padding: 10px;">' . deck_river_wire_filter_external($parent_post->description) . '</div>' . "\n\n";

	return $body;
}



/**
 * Send notification to poster of parent post if not notified already
 *
 * @param int      $guid        The guid of the reply wire post
 * @param int      $parent_guid The guid of the original wire post
 * @param ElggUser $user        The user who posted the reply
 * @return void
 */
function deck_river_thewire_send_response_notification($guid, $parent_guid, $user) {
	$parent_owner = get_entity($parent_guid)->getOwnerEntity();
	if (!$user) $user = elgg_get_logged_in_user_entity();
	// check to make sure user is not responding to self
	if ($parent_owner->guid != $user->guid) {
		// check if parent owner has notification for this user
		$send_response = true;
		global $NOTIFICATION_HANDLERS;
		foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
			if (check_entity_relationship($parent_owner->guid, 'notify' . $method, $user->guid)) {
				$send_response = false;
			}
		}

		// create the notification message
		if ($send_response) {
			$msg = deck_river_thewire_notify_message($guid, $parent_guid);

			notify_user(
					$parent_owner->guid,
					$user->guid,
					elgg_echo('thewire:notify:subject', array($user->username)),
					$msg);
		}
	}
}



/**
 * Returns the mention body
 *
 * @return $string
 */
function deck_river_thewire_mention_message($guid, $user_mentioned) {
	$entity = get_entity($guid);
	$descr = deck_river_wire_filter_external($entity->description);
	$owner = $entity->getOwnerEntity();

	$parent_post = get_entity($parent_guid);

	$owner_url = elgg_view('output/url', array(
		'href' => $owner->getURL(),
		'text' => $owner->name,
		'is_trusted' => true,
	));
	$this_message = elgg_view('output/url', array(
		'href' => $entity->getURL(),
		'text' => elgg_echo('thewire:notify:thismessage'),
		'is_trusted' => true,
	));
	$body = elgg_echo('thewire:mention:mention', array($owner_url, $this_message));
	$body .= "\n\n" . '<div style="background-color: #FAFAFA;font-size: 1.4em;padding: 10px;">' . $descr . '</div>' . "\n";

	return $body;
}



/**
 * Send notification to mentioned user
 *
 * @param int      $guid        The guid of the reply wire post
 * @param ElggUser $user        The user mentioned
 * @return void
 */
function deck_river_thewire_send_mention_notification($guid, $user_mentioned) {
	$owner = get_entity($guid)->getOwnerEntity();
	// check to make sure user is not mentionning to self
	if ($owner->guid != $user_mentioned->guid) {
		// create the notification message
		$msg = deck_river_thewire_mention_message($guid, $user_mentioned);

		notify_user(
				$user_mentioned->guid,
				$owner->guid,
				elgg_echo('thewire:mention:subject', array($owner->username)),
				$msg);
	}
}



/**
 * Add a menu item to an ownerblock
 *
 * @return array
 */
function deck_river_thewire_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "message/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('thewire', elgg_echo('item:object:thewire'), $url);
		$return[] = $item;
	}

	return $return;
}



/**
 * Add the comment and delete links to river actions menu
 * @access private
 */
function deck_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];
		$object = $item->getObjectEntity();

		if ($object->getSubtype() == 'thewire') {
			$options = array(
				'name' => 'response',
				'text' => elgg_view_icon('response'),
				'class' => 'gwfb tooltip s',
				'title' => elgg_echo('reply'),
				'priority' => 50,
			);
			$return[] = ElggMenuItem::factory($options);

			$options = array(
				'name' => 'retweet',
				'text' => elgg_view_icon('retweet'),
				'class' => 'gwfb tooltip s',
				'title' => elgg_echo('retweet'),
				'priority' => 60,
			);
			$return[] = ElggMenuItem::factory($options);

			if (preg_match('/@\w{1,}/', $object->description)) {
				$options = array(
					'name' => 'response-all',
					'href' => '#',
					'text' => elgg_view_icon('response-all') . elgg_echo('replyall'),
					'priority' => 50,
				);
				$reply = ElggMenuItem::factory($options);
			}

			if ($object->canEdit()) {
				$options = array(
					'name' => 'delete',
					'href' => "action/message/delete?guid=$object->guid",
					'text' => elgg_view_icon('delete') . elgg_echo('delete'),
					'title' => elgg_echo('delete'),
					'confirm' => elgg_echo('deleteconfirm'),
					'is_action' => true,
					'priority' => 200,
				);
				$del = ElggMenuItem::factory($options);
			}

			if ($reply || $del) {
				$options = array(
					'name' => 'submenu',
					'href' => '#',
					'text' => '+',
					'priority' => 200,
				);
				$submenu = ElggMenuItem::factory($options);
			}
			if ($reply) $submenu->addChild($reply);
			if ($del) $submenu->addChild($del);

			$return[] = $submenu;

		} else if ($object->getType() == 'object') {

			// comments
			if ($object->canComment()) {
				$options = array(
					'name' => 'comment',
					'href' => $object->getURL() . "#comments",
					'text' => elgg_view_icon('speech-bubble'),
					'class' => 'gwfb tooltip s',
					'title' => elgg_echo('comment:this'),
					'priority' => 50,
				);
				$return[] = ElggMenuItem::factory($options);
			}

			$options = array(
				'name' => 'share',
				'text' => elgg_view_icon('share'),
				'class' => 'gwfb tooltip s',
				'title' => elgg_echo('menu:shortlink'),
				'priority' => 50,
			);
			$return[] = ElggMenuItem::factory($options);

		}
	}

	return $return;
}

function deck_return_menu(array $vars = array(), $sort_by = 'priority') {
	// Give plugins a chance to add menu items just before creation.
	// This supports dynamic menus (example: user_hover).
	$menu = elgg_trigger_plugin_hook('register', 'menu:river', $vars, $menu);

	$builder = new ElggMenuBuilder($menu);
	$vars['menu'] = $builder->getMenu($sort_by);

	// Let plugins modify the menu
	$vars['menu'] = elgg_trigger_plugin_hook('prepare', 'menu:river', $vars, $vars['menu']);

	foreach ($vars['menu'] as $section => $menu_items) {
		foreach ($menu_items as $key => $item) {
			if ($childs = $item->getChildren()) {
				foreach ($childs as $child_item) {
					$ch[] = array(
						'name' => $child_item->getName(),
						'content' => $child_item->getContent()
					);
				}
				$return[] = array(
					'name' => $item->getName(),
					'text' => $item->getText(),
					'sub' => true,
					'childs' => $ch
				);
			} else {
				$return[] = array(
					'name' => $item->getName(),
					'title' => $item->getTooltip(),
					'selected' => $item->getSelected() ? true : false
				);
			}
		}
	}
	return $return;
}



/**
* Google url shortener
* http://www.webgalli.com/blog/easily-create-short-urls-with-php-curl-and-goo-gl-or-bit-ly/
*/
function goo_gl_short_url($longUrl) {
	$GoogleApiKey = elgg_get_plugin_setting('googleApiKey', 'elgg-deck_river');
	$postData = array('longUrl' => $longUrl, 'key' => $GoogleApiKey);
	$jsonData = json_encode($postData);
	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($curlObj, CURLOPT_POST, 1);
	curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
	$response = curl_exec($curlObj);
	$json = json_decode($response);
	curl_close($curlObj);
	return $json->id;
}



/**
 * Return token of the site twitter account specified in plugin settings
 * @return [type] [description]
 */
function deck_river_get_site_twitter_account() {
	$ia = elgg_set_ignore_access(true);

	// get token and secret of specified account
	$my_network_account = get_entity(elgg_get_plugin_setting('twitter_my_network_account', 'elgg-deck_river'));
	$token = array(
		'oauth_token' => $my_network_account->oauth_token,
		'oauth_token_secret' => $my_network_account->oauth_token_secret
	);

	elgg_set_ignore_access($ia);

	return $token;
}



/**
 * Auto follow each new twitter account
 * @return void
 */
function deck_river_autofollow_twitter_account($event, $type, $params) {
	$twitter_account = $params->screen_name;

	if ($twitter_account && elgg_get_plugin_setting('twitter_auto_follow', 'elgg-deck_river') && elgg_get_plugin_setting('twitter_my_network_account', 'elgg-deck_river')) {
		$token = deck_river_get_site_twitter_account();

		$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
		$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');
		elgg_load_library('deck_river:twitter_async');

		try {
			$twitterObj = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret, $token['oauth_token'], $token['oauth_token_secret']);
			$follow = $twitterObj->post('/friendships/create.json', array('screen_name' => $twitter_account));
		} catch(Exception $e) {
		}
	}

}


function deck_river_access_collections($hook, $type, $return, $params) {
	foreach($return as $access_id => $access_name) {
		if ($access_name == 'shared_network_acl') unset($return[$access_id]);
	}
	return $return;
}


