<?php

function facebook_theme_init() {
	/**
	 * Customize pages
	 */
	elgg_register_plugin_hook_handler('index', 'system', 'facebook_theme_index_handler');
	elgg_register_page_handler('profile', 'facebook_theme_profile_page_handler');
	elgg_register_page_handler('dashboard', 'facebook_theme_dashboard_handler');
	
	//What a hack!  Overriding groups page handler without blowing away other plugins doing the same
	global $CONFIG, $facebook_theme_original_groups_page_handler;
	$facebook_theme_original_groups_page_handler = $CONFIG->pagehandler['groups'];
	elgg_register_page_handler('groups', 'facebook_theme_groups_page_handler');
	
	elgg_register_ajax_view('thewire/composer');
	elgg_register_ajax_view('messageboard/composer');
	elgg_register_ajax_view('blog/composer');
	elgg_register_ajax_view('file/composer');
	elgg_register_ajax_view('bookmarks/composer');
	
	/**
	 * Customize menus
	 */
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
	
	elgg_register_plugin_hook_handler('register', 'menu:river', 'facebook_theme_river_menu_handler');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'facebook_theme_owner_block_menu_handler', 600);
	elgg_register_plugin_hook_handler('register', 'menu:composer', 'facebook_theme_composer_menu_handler');
	
	elgg_register_event_handler('pagesetup', 'system', 'facebook_theme_pagesetup_handler', 1000);
	
	/**
	 * Customize permissions
	 */
	elgg_register_plugin_hook_handler('permissions_check:annotate', 'all', 'facebook_theme_annotation_permissions_handler');
	elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'facebook_theme_container_permissions_handler');
	
	/**
	 * Miscellaneous customizations
	 */
	//Small "correction" to groups profile -- brief description makes more sense to come first!
	elgg_register_plugin_hook_handler('profile:fields', 'group', 'facebook_theme_group_profile_fields', 1);
		
	//@todo report some of the extra patterns to be included in Elgg core
	elgg_extend_view('css/elgg', 'facebook_theme/css');
	elgg_extend_view('js/elgg', 'js/topbar');
	
	//Likes summary bar -- "You, John, and 3 others like this"
	if (elgg_is_active_plugin('likes')) {
		elgg_extend_view('river/elements/responses', 'likes/river_footer', 1);
	}
	
	elgg_extend_view('river/elements/responses', 'discussion/river_footer');
	
	//Elgg only includes the search bar in the header by default,
	//but we usually don't show the header when the user is logged in
	if (elgg_is_active_plugin('search')) {
		elgg_extend_view('page/elements/topbar', 'search/search_box');
		elgg_unextend_view('page/elements/header', 'search/search_box');
		
		if (!elgg_is_logged_in()) {
			elgg_unextend_view('page/elements/header', 'search/header');
		}
	}
}

function facebook_theme_groups_page_handler($segments, $handle) {
	$pages_dir = dirname(__FILE__) . '/pages';

	switch ($segments[0]) {
		case 'profile':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/wall.php";
			break;
			
		case 'info':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/info.php";
			break;
			
		case 'discussion':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/discussion.php";
			break;
			
		default:
			global $facebook_theme_original_groups_page_handler;
			return call_user_func($facebook_theme_original_groups_page_handler, $segments, $handle);
	}
	return true;
}

function facebook_theme_pagesetup_handler() {
	$owner = elgg_get_page_owner_entity();

	if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		elgg_register_menu_item('page', array(
			'name' => 'news',
			'text' => elgg_view_icon('newsfeed') . elgg_echo('newsfeed'),
			'href' => '/dashboard',
			'priority' => 100,
			'contexts' => array('dashboard'),
		));
		if (elgg_is_active_plugin('messages')) {
			elgg_register_menu_item('page', array(
				'name' => 'messages',
				'text' => elgg_view_icon('messages') . elgg_echo('messages'),
				'href' => "/messages/inbox/$user->username",
				'contexts' => array('dashboard'),
			));
		}
		
		elgg_register_menu_item('page', array(
			'name' => 'friends',
			'text' => elgg_view_icon('friends') . elgg_echo('friends'),
			'href' => "/friends/$user->username",
			'priority' => 500,
			'contexts' => array('dashboard'),
		));
	
		if ($owner instanceof ElggUser && $owner->guid != $user->guid) {
			
			if (check_entity_relationship($user->guid, 'friend', $owner->guid)) {
				elgg_register_menu_item('extras', array(
					'name' => 'removefriend',
					'text' => elgg_echo('friend:remove'),
					'href' => "/action/friends/remove?friend=$owner->guid",
					'is_action' => TRUE,
					'contexts' => array('profile'),
				));
			} else {
				elgg_register_menu_item('title', array(
					'name' => 'addfriend',
					'text' => elgg_view_icon('addfriend') . elgg_echo('friend:add'),
					'href' => "/action/friends/add?friend=$owner->guid",
					'is_action' => TRUE,
					'link_class' => 'elgg-button elgg-button-special',
					'contexts' => array('profile'),
					'priority' => 1,
				));
			}
			
			if (elgg_is_active_plugin('messages')) {
				elgg_register_menu_item('title', array(
					'name' => 'message',
					'text' => elgg_view_icon('messages') . elgg_echo('messages:message'),
					'href' => "/messages/compose?send_to=$owner->guid",
					'link_class' => 'elgg-button elgg-button-action',
					'contexts' => array('profile'),
				));
			}
		}
		
		if ($owner->guid == $user->guid) {
			elgg_register_menu_item('title', array(
				'name' => 'editprofile',
				'href' => "/profile/$user->username/edit",
				'text' => elgg_echo('profile:edit'),
				'link_class' => 'elgg-button elgg-button-action',
				'contexts' => array('profile'),
			));
		}
		
		if (elgg_is_active_plugin('groups')) {
			$groups = $user->getGroups('', 4);
			
			foreach ($groups as $group) {
				elgg_register_menu_item('page', array(
					'section' => 'groups',
					'name' => "group-$group->guid",
					'text' => elgg_view_icon('users') . $group->name,
					'href' => $group->getURL(),
					'contexts' => array('dashboard'),
				));
			}
			
			elgg_register_menu_item('page', array(
				'name' => 'groups-add',
				'section' => 'groups',
				'text' => elgg_view_icon('addgroup') . elgg_echo('groups:add'),
				'href' => "/groups/add",
				'contexts' => array('dashboard'),
				'priority' => 499,
			));
			
			elgg_register_menu_item('page', array(
				'section' => 'groups',
				'name' => 'groups',
				'text' => elgg_echo('see:all'),
				'href' => "/groups/member/$user->username",
				'contexts' => array('dashboard'),
				'priority' => 500,
			));
		}
		
		if (elgg_is_active_plugin('tidypics')) {
			elgg_register_menu_item('page', array(
				'section' => 'more',	
				'name' => 'photos',
				'text' => elgg_view_icon('photo') . elgg_echo("photos"),
				'href' => "/photos/friends/$user->username",
				'contexts' => array('dashboard'),
			));
		}
		
		if (elgg_is_active_plugin('bookmarks')) {
			elgg_register_menu_item('page', array(
				'section' => 'more',
				'name' => 'bookmarks',
				'text' => elgg_view_icon('push-pin') . elgg_echo('bookmarks'),	
				'href' => "/bookmarks/friends/$user->username",
				'contexts' => array('dashboard'),
			));
		}
		
		if (elgg_is_active_plugin('blog')) {
			elgg_register_menu_item('page', array(
				'section' => 'more',	
				'name' => 'blog',
				'text' => elgg_view_icon('speech-bubble-alt') . elgg_echo('blog'),
				'href' => "/blog/friends/$user->username",
				'contexts' => array('dashboard'),
			));
		}
		
		if (elgg_is_active_plugin('pages')) {
			elgg_register_menu_item('page', array(
				'section' => 'more',	
				'name' => 'pages',
				'text' => elgg_view_icon('list') . elgg_echo('pages'),
				'href' => "/pages/friends/$user->username",
				'contexts' => array('dashboard'),
			));
		}
		
		if (elgg_is_active_plugin('file')) {
			elgg_register_menu_item('page', array(
				'section' => 'more',	
				'name' => 'files',
				'text' => elgg_view_icon('clip') . elgg_echo('files'),
				'href' => "/file/friends/$user->username",
				'contexts' => array('dashboard'),
			));
		}

		if (elgg_is_active_plugin('thewire')) {
			elgg_register_menu_item('page', array(
				'section' => 'more',
				'name' => 'thewire',
				'text' => elgg_view_icon('share') . elgg_echo('Wire'),
				'href' => "/thewire/friends/$user->username",
				'contexts' => array('dashboard'),
			));
		}
		
		$address = urlencode(current_page_url());
		
		if (elgg_is_active_plugin('bookmarks')) {
			elgg_register_menu_item('extras', array(
				'name' => 'bookmark',
				'text' => elgg_view_icon('link') . elgg_echo('bookmarks:this'),
				'href' => "bookmarks/add/$user->guid?address=$address",
				'title' => elgg_echo('bookmarks:this'),
				'rel' => 'nofollow',
			));
		}
		
		if (elgg_is_active_plugin('reportedcontent')) {
			elgg_unregister_menu_item('footer', 'report_this');
		
			$href = "javascript:elgg.forward('reportedcontent/add'";
			$href .= "+'?address='+encodeURIComponent(location.href)";
			$href .= "+'&title='+encodeURIComponent(document.title));";
				
			elgg_register_menu_item('extras', array(
				'name' => 'report_this',
				'href' => $href,
				'text' => elgg_view_icon('report-this') . elgg_echo('reportedcontent:this'),
				'title' => elgg_echo('reportedcontent:this:tooltip'),
				'priority' => 500,
			));
		}
		
		/**
		 * TOPBAR customizations
		 */
		//Want our logo present, not Elgg's
		$site = elgg_get_site_entity();
		elgg_unregister_menu_item('topbar', 'elgg_logo');
		elgg_register_menu_item('topbar', array(
			'href' => '/',
			'name' => 'logo',
			'priority' => 1,
			'text' => "<h1 id=\"facebook-topbar-logo\">$site->name</h1>",
		));
	
		elgg_register_menu_item('topbar', array(
			'href' => '/dashboard',
			'name' => 'home',
			'priority' => 2,
			'section' => 'alt',
			'text' => elgg_echo('home'),
		));
		
		if (elgg_is_active_plugin('profile')) {
			elgg_unregister_menu_item('topbar', 'profile');
			elgg_register_menu_item('topbar', array(
				'name' => 'profile',
				'section' => 'alt',
				'text' => "<img src=\"{$user->getIconURL('topbar')}\" class=\"elgg-icon elgg-inline-block\" alt=\"$user->name\"/>" . $user->name,
				'href' => "/profile/$user->username",
				'priority' => 1,
			));
		}
		
		elgg_register_menu_item('topbar', array(
			'href' => "#",
			'name' => 'account',
			'priority' => 1000,
			'section' => 'alt',
			'text' => '',
		));

		elgg_unregister_menu_item('topbar', 'usersettings');
		elgg_register_menu_item('topbar', array(
			'href' => "/settings/user/$user->username",
			'name' => 'usersettings',
			'parent_name' => 'account',
			'section' => 'alt',
			'text' => elgg_echo('settings:user'),
		));
		
		if (elgg_is_active_plugin('notifications')) {
			elgg_register_menu_item('topbar', array(
				'href' => "/notifications/personal",
				'name' => 'notifications',
				'parent_name' => 'account',
				'section' => 'alt',
				'text' => elgg_echo('notifications:personal'),
			));
		}
		
		elgg_unregister_menu_item('topbar', 'logout');
		elgg_register_menu_item('topbar', array(
			'href' => '/action/logout',
			'is_action' => TRUE,
			'name' => 'logout',
			'parent_name' => 'account',
			'priority' => 1000, //want this to be at the bottom of the list no matter what
			'section' => 'alt',
			'text' => elgg_echo('logout'),
		));
	}
	
}

function facebook_theme_dashboard_handler() {
	require_once dirname(__FILE__) . '/pages/dashboard.php';
	return true;
}

function facebook_theme_index_handler() {
	if (elgg_is_logged_in()) {
		forward('/dashboard');
	}
}

function facebook_theme_container_permissions_handler($hook, $type, $result, $params) {
	$container = $params['container'];
	$subtype = $params['subtype'];
	
	if ($container instanceof ElggGroup) {
		if ($subtype == 'thewire') {
			return false;
		}
	}
}

function facebook_theme_annotation_permissions_handler($hook, $type, $result, $params) {
	$entity = $params['entity'];
	$user = $params['user'];
	$annotation_name = $params['annotation_name'];
	
	//Users should not be able to post on their own message board
	if ($annotation_name == 'messageboard' && $user->guid == $entity->guid) {
		return false;
	}
	
	//No "commenting" on users, must use messageboard
	if ($annotation_name == 'generic_comment' && $entity instanceof ElggUser) {
		return false;
	}
	
	//No "commenting" on forum topics, must use special "reply" annotation
	if ($annotation_name == 'generic_comment' && elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return false;
	}
	
	//Definitely should be able to "like" a forum topic!
	if ($annotation_name == 'likes' && elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return true;
	}
	
	if ($annotation_name == 'group_topic_post' && !elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return false;
	}
}

/**
 * Adds menu items to the "composer" at the top of the "wall".  Need to also add
 * the forms that these items point to.
 * 
 * @todo Get the composer concept integrated into core
 */
function facebook_theme_composer_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];
	
	if (elgg_is_active_plugin('thewire') && $entity->canWriteToContainer(0, 'object', 'thewire')) {
		$items[] = ElggMenuItem::factory(array(
			'name' => 'thewire',
			'href' => "/ajax/view/thewire/composer?container_guid=$entity->guid",
			'text' => elgg_view_icon('share') . elgg_echo("composer:object:thewire"),
			'priority' => 100,
		));
		
		//trigger any javascript loads that we might need
		elgg_view('thewire/composer');
	}
	
	if (elgg_is_active_plugin('messageboard') && $entity->canAnnotate(0, 'messageboard')) {
		$items[] = ElggMenuItem::factory(array(
			'name' => 'messageboard',
			'href' => "/ajax/view/messageboard/composer?entity_guid=$entity->guid",
			'text' => elgg_view_icon('speech-bubble-alt') . elgg_echo("composer:annotation:messageboard"),
			'priority' => 200,
		));
		
		//trigger any javascript loads that we might need
		elgg_view('messageboard/composer');
	}
	
	if (elgg_is_active_plugin('bookmarks') && $entity->canWriteToContainer(0, 'object', 'bookmarks')) {
		$items[] = ElggMenuItem::factory(array(
			'name' => 'bookmarks',
			'href' => "/ajax/view/bookmarks/composer?container_guid=$entity->guid",
			'text' => elgg_view_icon('push-pin') . elgg_echo("composer:object:bookmarks"),
			'priority' => 300,
		));
		
		//trigger any javascript loads that we might need
		elgg_view('bookmarks/composer');
	}
	
	if (elgg_is_active_plugin('blog') && $entity->canWriteToContainer(0, 'object', 'blog')) {
		$items[] = ElggMenuItem::factory(array(
			'name' => 'blog',
			'href' => "/ajax/view/blog/composer?container_guid=$entity->guid",
			'text' => elgg_view_icon('speech-bubble') . elgg_echo("composer:object:blog"),
			'priority' => 600,
		));
		
		//trigger any javascript loads that we might need
		elgg_view('blog/composer');
	}
	
	if (elgg_is_active_plugin('file') && $entity->canWriteToContainer(0, 'object', 'file')) {
		$items[] = ElggMenuItem::factory(array(
			'name' => 'file',
			'href' => "/ajax/view/file/composer?container_guid=$entity->guid",
			'text' => elgg_view_icon('clip') . elgg_echo("composer:object:file"),
			'priority' => 700,
		));
		
		//trigger any javascript loads that we might need
		elgg_view('file/composer');
	}
	
	return $items;
}

function facebook_theme_group_profile_fields($hook, $type, $fields, $params) {
	return array(
		'briefdescription' => 'text',
		'description' => 'longtext',
		'interests' => 'tags',
	);
}

function facebook_theme_owner_block_menu_handler($hook, $type, $items, $params) {
	$owner = elgg_get_page_owner_entity();
	
	if ($owner instanceof ElggGroup) {
		$items['info'] = ElggMenuItem::factory(array(
			'name' => 'info', 
			'text' => elgg_view_icon('info') . elgg_echo('profile:info'), 
			'href' => "/groups/info/$owner->guid/" . elgg_get_friendly_title($owner->name),
			'priority' => 2,
		));
		
		$items['profile'] = ElggMenuItem::factory(array(
			'name' => 'profile',
			'text' => elgg_view_icon('userwall') . elgg_echo('profile:wall'),
			'href' => "/groups/profile/$owner->guid/" . elgg_get_friendly_title($owner->name),
			'priority' => 1,
		));
	}
	
	if ($owner instanceof ElggUser) {
		$items['info'] = ElggMenuItem::factory(array(
			'name' => 'info', 
			'text' => elgg_view_icon('info') . elgg_echo('profile:info'), 
			'href' => "/profile/$owner->username/info",
			'priority' => 2,
		));
		
		$items['profile'] = ElggMenuItem::factory(array(
			'name' => 'profile',
			'text' => elgg_view_icon('userwall') . elgg_echo('profile:wall'),
			'href' => "/profile/$owner->username",
			'priority' => 1,
		));
		
		$items['friends'] = ElggMenuItem::factory(array(
			'name' => 'friends',	
			'text' => elgg_view_icon('friends') . elgg_echo('friends'),
			'href' => "/friends/$owner->username"
		));
	}
	
	$top_level_pages = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'page_top',
		'container_guid' => $owner->guid,
		'limit' => 0,
	));
	
	foreach ($top_level_pages as $page) {
		$items["pages-$page->guid"] = ElggMenuItem::factory(array(
			'name' => "pages-$page->guid",
			'href' => $page->getURL(),
			'text' => elgg_view_icon('page') . elgg_view('output/text', array('value' => $page->title)),
		));
	}
	
	return $items;
	
}

function facebook_theme_river_menu_handler($hook, $type, $items, $params) {
	$item = $params['item'];

	$object = $item->getObjectEntity();
	if (!elgg_in_context('widgets') && !$item->annotation_id && $object instanceof ElggEntity) {
		
		if (elgg_is_active_plugin('likes') && $object->canAnnotate(0, 'likes')) {
			if (!elgg_annotation_exists($object->getGUID(), 'likes')) {
				// user has not liked this yet
				$options = array(
					'name' => 'like',
					'href' => "action/likes/add?guid={$object->guid}",
					'text' => elgg_echo('likes:likethis'),
					'is_action' => true,
					'priority' => 100,
				);
			} else {
				// user has liked this
				$options = array(
					'name' => 'like',
					'href' => "action/likes/delete?guid={$object->guid}",
					'text' => elgg_echo('likes:remove'),
					'is_action' => true,
					'priority' => 100,
				);
			}
			
			$items[] = ElggMenuItem::factory($options);
		}
		
		if ($object->canAnnotate(0, 'generic_comment')) {
			$items[] = ElggMenuItem::factory(array(
				'name' => 'comment',
				'href' => "#comments-add-$object->guid",
				'text' => elgg_echo('comment'),
				'title' => elgg_echo('comment:this'),
				'rel' => "toggle",
				'priority' => 50,
			));
		}
		
		if ($object instanceof ElggUser && !$object->isFriend()) {
			$items[] = ElggMenuItem::factory(array(
				'name' => 'addfriend',
				'href' => "/action/friends/add?friend=$object->guid",
				'text' => elgg_view_icon('addfriend') . elgg_echo('friend:user:add', array($object->name)),
				'is_action' => TRUE,
			));
		}
		
		if (elgg_instanceof($object, 'object', 'groupforumtopic')) {
			$items[] = ElggMenuItem::factory(array(
				'name' => 'reply',
				'href' => "#groups-reply-$object->guid",
				'title' => elgg_echo('reply:this'),
				'text' => elgg_echo('reply'),
			));
		}
	}

	return $items;
}

/**
 * Profile page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function facebook_theme_profile_page_handler($page) {
	if (isset($page[0])) {
		$username = $page[0];
		$user = get_user_by_username($username);
		elgg_set_page_owner_guid($user->guid);
	}

	// short circuit if invalid or banned username
	if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
		register_error(elgg_echo('profile:notfound'));
		forward();
	}

	$action = NULL;
	if (isset($page[1])) {
		$action = $page[1];
	}

	switch ($action) {
		case 'edit':
			// use for the core profile edit page
			global $CONFIG;
			global $autofeed;
			$autofeed = false;
			require $CONFIG->path . 'pages/profile/edit.php';
			break;
		
		case 'info':
			require dirname(__FILE__) . '/pages/profile/info.php';
			break;
			
		case 'wall':
			require dirname(__FILE__) . '/pages/profile/wall.php';
			break;
			
		default:
			if (elgg_is_logged_in()) {
				require dirname(__FILE__) . '/pages/profile/wall.php';
			} else {
				require dirname(__FILE__) . '/pages/profile/info.php';
			}
			break;
	}
	
	return true;
}

elgg_register_event_handler('init', 'system', 'facebook_theme_init');