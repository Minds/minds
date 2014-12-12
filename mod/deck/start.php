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

	$path = elgg_get_plugins_path() . 'deck/';
	elgg_register_library('deck_river:deck', $path . 'lib/deck.php');
	elgg_register_library('deck_river:river_loader', $path . 'lib/river_loader.php');
	elgg_register_library('deck_river:api', $path . 'lib/api.php');
	elgg_register_library('deck_river:authorize', $path . 'lib/authorize.php');
	elgg_register_library('deck_river:twitter_async', $path . 'vendors/load_twitter_async.php');
	elgg_register_library('deck_river:facebook_sdk', $path . 'vendors/facebook-php-sdk/src/facebook.php');

	elgg_register_library('deck_river:tumblr_api', $path . 'vendors/TumblrOAuth/tumblroauth/tumblroauth.php');
	elgg_register_library('deck_river:linkedin_api', $path . 'vendors/PHP-LinkedIn-SDK/LinkedIn/LinkedIn.php');
	elgg_register_library('deck_river:minds_open_sdk', $path . 'vendors/minds_php_sdk/src/mindsOpen.php');
	
	elgg_register_library('alphaGUID', $path . 'vendors/alphaID.inc.php');

	elgg_load_library('deck_river:deck');
	elgg_load_library('alphaGUID');

	elgg_extend_view('css/elgg','deck_river/css');
	
	$js = elgg_get_simplecache_url('js', 'deck');
	elgg_register_js('deck:js', $js);
	elgg_extend_view('js/elgg', 'deck_river/js');
	

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
	elgg_register_ajax_view('deck_river/networks/tumblr/tumblr_OAuth');

	// register page handlers
	elgg_register_page_handler('activity', 'deck_river_page_handler');
	elgg_register_page_handler('scheduler', 'deck_river_scheduler_page_handler');
	elgg_register_page_handler('message', 'deck_river_wire_page_handler');
	elgg_register_page_handler('authorize', 'authorize_page_handler');
	elgg_register_page_handler('u', 'alphaGUID_page_handler');
	elgg_register_page_handler('bookmarklet', 'bookmarklet_handler');

	// register actions
	$action_path = elgg_get_plugins_path() . 'deck/actions';
	elgg_register_action('post/preview', "$action_path/post/preview.php");
	elgg_register_action('deck_river/add_message', "$action_path/post/add.php");
	elgg_register_action('message/delete', "$action_path/message/delete.php");
	elgg_register_action('deck_river/column/settings', "$action_path/column/settings.php");
	elgg_register_action('deck_river/column/move', "$action_path/column/move.php");
	elgg_register_action('deck_river/column/delete', "$action_path/column/delete.php");
	elgg_register_action('deck_river/tab/add', "$action_path/tab/add.php");
	elgg_register_action('deck_river/tab/delete', "$action_path/tab/delete.php");
	elgg_register_action('deck_river/tab/rename', "$action_path/tab/rename.php");
	elgg_register_action('deck_river/network/action', "$action_path/network/action.php");
	elgg_register_action('elgg-deck_river/settings/save', "$action_path/plugins/save.php");
	
	elgg_register_action('deck_river/post/add', "$action_path/post/add.php");

	// owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'deck_river_thewire_owner_block_menu');

	// unregistrer trigger for river menu
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'deck_river_menu_setup');

	// autofollow twitter account
	elgg_register_event_handler('authorize', 'deck_river:twitter', 'deck_river_autofollow_twitter_account');

	// register acces hook
	elgg_register_plugin_hook_handler('access:collections:write', 'user', 'deck_river_access_collections');
	
	//run the cron script every minute...
	//elgg_register_plugin_hook_handler('cron', 'minute', 'deck_cron_handler');
	
	$cassandra = datalist_get('cassandra'); //for cassandra ported

	deck_river_register_network(array('type'=>'object', 'subtype'=>'twitter_account', 'class'=>'ElggDeckTwitter', 'name'=>'twitter'));
	deck_river_register_network(array('type'=>'object', 'subtype'=>'facebook_account', 'class'=>'ElggDeckFacebook', 'name'=>'facebook'));
	deck_river_register_network(array('type'=>'object', 'subtype'=>'tumblr_account', 'class'=>'ElggDeckTumblr', 'name'=>'tumblr'));
	deck_river_register_network(array('type'=>'object', 'subtype'=>'linkedin_account', 'class'=>'ElggDeckLinkedin', 'name'=>'linkedin'));
	deck_river_register_network(array('type'=>'object', 'subtype'=>'minds_account', 'class'=>'ElggDeckMinds', 'name'=>'minds'));

	add_subtype('object','deck_tab','ElggDeckTab');
	add_subtype('object','deck_column','ElggDeckColumn');
	add_subtype('object','deck_post','ElggDeckPost');
	//add_subtype('object','facebook_page_account','ElggDeckFacebookPage');
}


function deck_river_page_handler($page) {
	
	elgg_load_js('deck:js');
	elgg_extend_view('page/elements/foot', 'deck_river/mustaches_wrapper', 499);
	elgg_extend_view('page/elements/foot', 'page/layouts/content/deck_river_add_new_tab', 500);
	
	if (elgg_is_logged_in()) {

		elgg_set_context($page[0]);
		include_once dirname(__FILE__) . '/pages/river.php';

	} else {
		forward('');
	}

	return true;
}

function deck_river_scheduler_page_handler($page){
	include(dirname(__FILE__) . '/pages/scheduled.php');
	return true;
}

/**
 * Serves pages for social network authorization.
 *
 * @param array $page
 * @return void
 */
function authorize_page_handler($page) {

	//needs urgent attention
	return false; 


	if (!isset($page[0])) {
		return false;
	}
	switch ($page[0]) {
		case 'applications':
			include elgg_get_plugins_path() . 'deck/pages/applications.php';
			break;
		default:
			$classname = "ElggDeck". ucfirst($page[0]);

			if(class_exists($classname)){
				$class = new $classname($page[1]);
			} else {
				return false;
			}
			switch($page[2]){
				case 'revoke':
					$class->revoke();
					break;
				case 'refresh':
					$class->refresh();
					break;
				case 'authenticate':
				default:
					$class->authenticate();
			}
				return true;
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
	return;
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

/**
 * 
 */
function deck_cron_handler($hook, $type, $return, $params){
	elgg_set_ignore_access();
	//get the posts
	$posts = deck_get_scheduled_list();
	foreach($posts as $post){
		if($post->ts <= time()){
			//we've passed the timestamp, so post
			$post->doPost();
			$post->delete();
			echo "Posted $post->guid \n";
		}
	}
}

/**
 * Register networks
 */
function deck_river_register_network(array $params = array()){
	global $deck_networks;
	add_subtype($params['type'], $params['subtype'], $params['class']);
	$deck_networks[$params['name']] = $params;
}

