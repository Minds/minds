<?php
/**
 * Photo Gallery plugin
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_register_event_handler('init', 'system', 'tidypics_init');

/**
 * Tidypics plugin initialization
 */
function tidypics_init() {
	// Register libraries
	$base_dir = elgg_get_plugins_path() . 'tidypics/lib';
	elgg_register_library('tidypics:core', "$base_dir/tidypics.php");
	elgg_register_library('tidypics:upload', "$base_dir/upload.php");
	elgg_register_library('tidypics:resize', "$base_dir/resize.php");
	elgg_register_library('tidypics:exif', "$base_dir/exif.php");
	elgg_load_library('tidypics:core');

	// Set up site menu
	elgg_register_menu_item('site', array(
		'name' => 'photos',
		'href' => 'photos/all',
		'text' => elgg_echo('photos'),
	));

	// Register a page handler so we can have nice URLs
	elgg_register_page_handler('photos', 'tidypics_page_handler');

	// Extend CSS
	elgg_extend_view('css/elgg', 'photos/css');
	elgg_extend_view('css/admin', 'photos/css');

	// Register the JavaScript lib
	$js = elgg_get_simplecache_url('js', 'photos/tidypics');
	elgg_register_simplecache_view('js/photos/tidypics');
	elgg_register_js('tidypics', $js, 'footer');
	$js = elgg_get_simplecache_url('js', 'photos/tagging');
	elgg_register_simplecache_view('js/photos/tagging');
	elgg_register_js('tidypics:tagging', $js, 'footer');
	$js = elgg_get_simplecache_url('js', 'photos/uploading');
	elgg_register_simplecache_view('js/photos/uploading');
	elgg_register_js('tidypics:uploading', $js, 'footer');

	elgg_register_js('tidypics:slideshow', 'mod/tidypics/vendors/PicLensLite/piclens_optimized.js', 'footer');
	elgg_register_js('swfobject', 'mod/tidypics/vendors/uploadify/swfobject.js', 'footer');
	elgg_register_js('jquery.uploadify-tp', 'mod/tidypics/vendors/uploadify/jquery.uploadify.v2.1.1.min.js', 'footer');

	// Add photos link to owner block/hover menus
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'tidypics_owner_block_menu');

	// Add admin menu item
	elgg_register_admin_menu_item('configure', 'photos', 'settings');

	// Register for search
	elgg_register_entity_type('object', 'image');
	elgg_register_entity_type('object', 'album');

	// Register for the entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'tidypics_entity_menu_setup');

	// Register group option
	add_group_tool_option('photos', elgg_echo('tidypics:enablephotos'), true);
	elgg_extend_view('groups/tool_latest', 'photos/group_module');

	// Register widgets
	elgg_register_widget_type('album_view', elgg_echo("tidypics:widget:albums"), elgg_echo("tidypics:widget:album_descr"), 'profile');
	elgg_register_widget_type('latest_photos', elgg_echo("tidypics:widget:latest"), elgg_echo("tidypics:widget:latest_descr"), 'profile');

	// RSS extensions for embedded media
	elgg_extend_view('extensions/xmlns', 'extensions/photos/xmlns');

	// allow group members add photos to group albums
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'tidypics_group_permission_override');
	elgg_register_plugin_hook_handler('permissions_check:metadata', 'object', 'tidypics_group_permission_override');

	// notifications
	register_notification_object('object', 'album', elgg_echo('tidypics:newalbum_subject'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'tidypics_notify_message');

	// allow people in a walled garden to use flash uploader
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'tidypics_walled_garden_override');

	// flash session work around for uploads when use_only_cookies is set
	elgg_register_plugin_hook_handler('forward', 'csrf', 'tidypics_ajax_session_handler');

	// Register actions
	$base_dir = elgg_get_plugins_path() . 'tidypics/actions/photos';
	elgg_register_action("photos/delete", "$base_dir/delete.php");

	elgg_register_action("photos/album/save", "$base_dir/album/save.php");
	elgg_register_action("photos/album/sort", "$base_dir/album/sort.php");
	elgg_register_action("photos/album/set_cover", "$base_dir/album/set_cover.php");

	elgg_register_action("photos/image/upload", "$base_dir/image/upload.php");
	elgg_register_action("photos/image/save", "$base_dir/image/save.php");
	elgg_register_action("photos/image/ajax_upload", "$base_dir/image/ajax_upload.php", 'logged_in');
	elgg_register_action("photos/image/ajax_upload_complete", "$base_dir/image/ajax_upload_complete.php", 'logged_in');
	elgg_register_action("photos/image/tag", "$base_dir/image/tag.php");
	elgg_register_action("photos/image/untag", "$base_dir/image/untag.php");

	elgg_register_action("photos/batch/edit", "$base_dir/batch/edit.php");

	elgg_register_action("photos/admin/settings", "$base_dir/admin/settings.php", 'admin');
	elgg_register_action("photos/admin/create_thumbnails", "$base_dir/admin/create_thumbnails.php", 'admin');
	elgg_register_action("photos/admin/upgrade", "$base_dir/admin/upgrade.php", 'admin');
}

/**
 * Tidypics page handler
 *
 * @param array $page Array of url segments
 * @return bool
 */
function tidypics_page_handler($page) {

	if (!isset($page[0])) {
		return false;
	}

	elgg_load_js('tidypics');

	$base = elgg_get_plugins_path() . 'tidypics/pages/photos';
	switch ($page[0]) {
		case "all": // all site albums
		case "world":
			require "$base/all.php";
			break;

		case "owned":  // albums owned by container entity
		case "owner":
			require "$base/owner.php";
			break;

		case "friends": // albums of friends
			require "$base/friends.php";
			break;

		case "group": // albums of a group
			require "$base/owner.php";
			break;

		case "album": // view an album individually
			set_input('guid', $page[1]);
			elgg_load_js('tidypics:slideshow');
			require "$base/album/view.php";
			break;

		case "new":  // create new album
		case "add":
			set_input('guid', $page[1]);
			require "$base/album/add.php";
			break;

		case "edit": //edit image or album
			set_input('guid', $page[1]);
			$entity = get_entity($page[1]);
			switch ($entity->getSubtype()) {
				case 'album':
					require "$base/album/edit.php";
					break;
				case 'image':
					require "$base/image/edit.php";
					break;
				case 'tidypics_batch':
					require "$base/batch/edit.php";
					break;
				default:
					return false;
			}
			break;

		case "sort": // sort a photo album
			set_input('guid', $page[1]);
			require "$base/album/sort.php";
			break;

		case "image": //view an image
		case "view":
			set_input('guid', $page[1]);
			require "$base/image/view.php";
			break;

		case "thumbnail": // tidypics thumbnail
			set_input('guid', $page[1]);
			set_input('size', elgg_extract(2, $page, 'small'));
			require "$base/image/thumbnail.php";
			break;

		case "upload": // upload images to album
			set_input('guid', $page[1]);

			if (elgg_get_plugin_setting('uploader', 'tidypics')) {
				$default_uploader = 'ajax';
			} else {
				$default_uploader = 'basic';
			}

			set_input('uploader', elgg_extract(2, $page, $default_uploader));
			require "$base/image/upload.php";
			break;

		case "batch": //update titles and descriptions
			if (isset($page[1])) {
				set_input('batch', $page[1]);
			}
			include($CONFIG->pluginspath . "tidypics/pages/edit_multiple.php");
			break;

		case "download": // download an image
			set_input('guid', $page[1]);
			set_input('disposition', elgg_extract(2, $page, 'attachment'));
			include "$base/image/download.php";
			break;

		case "tagged": // all photos tagged with user
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include($CONFIG->pluginspath . "tidypics/pages/tagged.php");
			break;

		case "mostviewed": // images with the most views
			if (isset($page[1])) {
				set_input('username', $page[1]);
			}
			include($CONFIG->pluginspath . "tidypics/pages/lists/mostviewedimages.php");
			break;

		case "mostrecent": // images uploaded most recently
			if (isset($page[1])) {
				set_input('username', $page[1]);
			}
			include($CONFIG->pluginspath . "tidypics/pages/lists/mostrecentimages.php");
			break;

		case "recentlyviewed": // images most recently viewed
			include($CONFIG->pluginspath . "tidypics/pages/lists/recentlyviewed.php");
			break;

		case "recentlycommented": // images with the most recent comments
			include($CONFIG->pluginspath . "tidypics/pages/lists/recentlycommented.php");
			break;

		case "highestrated": // images with the highest average rating
			include($CONFIG->pluginspath . "tidypics/pages/lists/highestrated.php");
			break;

		case "admin":
			include ($CONFIG->pluginspath . "tidypics/pages/admin.php");
			break;

		default:
			return false;
	}
	
	return true;
}

/**
 * Add a menu item to an ownerblock
 */
function tidypics_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "photos/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('photos', elgg_echo('photos'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->photos_enable != "no") {
			$url = "photos/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('photos', elgg_echo('photos:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add Tidypics links/info to entity menu
 */
function tidypics_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'photos') {
		return $return;
	}

	if (elgg_instanceof($entity, 'object', 'image')) {
		$album = $entity->getContainerEntity();
		$cover_guid = $album->getCoverImageGuid();
		if ($cover_guid != $entity->getGUID() && $album->canEdit()) {
			$url = 'action/photos/album/set_cover'
				. '?image_guid=' . $entity->getGUID()
				. '&album_guid=' . $album->getGUID();

			$params = array(
				'href' => $url,
				'text' => elgg_echo('album:cover_link'),
				'is_action' => true,
				'is_trusted' => true,
				'confirm' => elgg_echo('album:cover')
			);
			$text = elgg_view('output/confirmlink', $params);

			$options = array(
				'name' => 'set_cover',
				'text' => $text,
				'priority' => 80,
			);
			$return[] = ElggMenuItem::factory($options);
		}

		if (elgg_get_plugin_setting('view_count', 'tidypics')) {
			$view_info = $entity->getViewInfo();
			$text = elgg_echo('tidypics:views', array((int)$view_info['total']));
			$options = array(
				'name' => 'views',
				'text' => "<span>$text</span>",
				'href' => false,
				'priority' => 90,
			);
			$return[] = ElggMenuItem::factory($options);
		}

		if (elgg_get_plugin_setting('tagging', 'tidypics')) {
			$options = array(
				'name' => 'tagging',
				'text' => elgg_echo('tidypics:actiontag'),
				'href' => '#',
				'title' => elgg_echo('tidypics:tagthisphoto'),
				'rel' => 'photo-tagging',
				'priority' => 80,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	// only show these options if there are images
	if (elgg_instanceof($entity, 'object', 'album') && $entity->getSize() > 0) {
		$url = $entity->getURL() . '?limit=50&view=rss';
		$url = elgg_format_url($url);
		$slideshow_link = "javascript:PicLensLite.start({maxScale:0, feedUrl:'$url'})";
		$options = array(
			'name' => 'slideshow',
			'text' => elgg_echo('album:slideshow'),
			'href' => $slideshow_link,
			'priority' => 80,
		);
		$return[] = ElggMenuItem::factory($options);

		if ($entity->canEdit()) {
			$options = array(
				'name' => 'sort',
				'text' => elgg_echo('album:sort'),
				'href' => "photos/sort/" . $entity->getGUID(),
				'priority' => 90,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Override permissions for group albums
 *
 * 1. To write to a container (album) you must be able to write to the owner of the container (odd)
 * 2. We also need to change metadata on the album
 *
 * @param string $hook
 * @param string $type
 * @param bool   $result
 * @param array  $params
 * @return mixed
 */
function tidypics_group_permission_override($hook, $type, $result, $params) {
	if (get_input('action') == 'photos/image/upload') {
		if (isset($params['container'])) {
			$album = $params['container'];
		} else {
			$album = $params['entity'];
		}

		if (elgg_instanceof($album, 'object', 'album')) {
			return $album->getContainerEntity()->canWriteToContainer();
		}
	}
}


/**
 * Create the body of the notification message
 *
 * Does not run if a new album without photos
 * 
 * @param string $hook
 * @param string $type
 * @param bool   $result
 * @param array  $params
 * @return mixed
 */
function tidypics_notify_message($hook, $type, $result, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	
	if (elgg_instanceof($entity, 'object', 'album')) {
		if ($entity->new_album) {
			// stops notification from being sent
			return false;
		}
		
		if ($entity->first_upload) {
			$descr = $entity->description;
			$title = $entity->getTitle();
			$owner = $entity->getOwnerEntity();
			return elgg_echo('tidypics:newalbum', array($owner->name))
					. ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		} else {
			if ($entity->shouldNotify()) {
				$descr = $entity->description;
				$title = $entity->getTitle();
				$owner = $entity->getOwnerEntity();

				return elgg_echo('tidypics:updatealbum', array($owner->name, $title)) . ': ' . $entity->getURL();
			}
		}
	}
	
	return null;
}

/**
 * Allows the flash uploader actions through walled garden since
 * they come without the session cookie
 */
function tidypics_walled_garden_override($hook, $type, $pages) {
	$pages[] = 'action/photos/image/ajax_upload';
	$pages[] = 'action/photos/image/ajax_upload_complete';
	return $pages;
}

/**
 * Work around for Flash/session issues
 *
 * Catches Elgg attempting to forward the Flash uploader because it doesn't
 * have a session cookie. Instead manually runs the action.
 *
 * @param string $hook   The name of the hook
 * @param string $type   The type of the hook
 * @param string $value  Location being forwarded to
 * @param array  $params Parameters related to the forward() call
 * @return void
 */
function tidypics_ajax_session_handler($hook, $type, $value, $params) {
    $www_root = elgg_get_config('wwwroot');
	$url = $params['current_url'];

    if ($url !== "{$www_root}action/photos/image/ajax_upload") {
        return;
    }

    if (elgg_get_logged_in_user_guid() != 0) {
        return;
    }

    // action_gatekeeper rejected ajax call from Flash due to session issue
    
	// Validate token
    $token = get_input('__elgg_token');
    $ts = get_input('__elgg_ts');
    $session_id = get_input('Elgg');
	$session_token = get_input('session_token');
	$tidypics_token = get_input('tidypics_token');
	$user_guid = get_input('user_guid');
	$user = get_user($user_guid);
	$timeout = elgg_get_config('action_token_timeout');
	if (!$timeout) {
		$timeout = 2;
	}

	if (!$user) {
		trigger_error('Tidypics warning: failed to get user in flash uploader', E_USER_WARNING);
		return;
	}

	if (!$token || !$ts || !$session_id || !$tidypics_token) {
		trigger_error('Tidypics warning: token information missing in flash uploader', E_USER_WARNING);
		return;
	}

	$hour = 60*60;
	$now = time();
	if ($ts < $now-$hour || $ts > $now+$hour) {
		trigger_error('Tidypics warning: failed time check in flash uploader', E_USER_WARNING);
		return;
	}

	$generated_token = md5($session_id . get_site_secret() . $ts . $user->salt);

	if ($tidypics_token !== $generated_token) {
		trigger_error('Tidypics warning: token check failed in flash uploader', E_USER_WARNING);
		return;
	}

	// passed token test, so login and process action
	login($user);
	$actions = elgg_get_config('actions');
	include $actions['photos/image/ajax_upload']['file'];

	exit;
}

/**
 * Sets up submenus for tidypics most viewed pages
 */
function tidypics_mostviewed_submenus() {

	global $CONFIG;

	add_submenu_item(elgg_echo('tidypics:mostvieweddashboard'), $CONFIG->url . "mod/tidypics/mostvieweddashboard.php");
	add_submenu_item(elgg_echo('tidypics:mostviewedthisyear'), $CONFIG->url . "mod/tidypics/pages/lists/mostviewedimagesthisyear.php");
	add_submenu_item(elgg_echo('tidypics:mostviewedthismonth'), $CONFIG->url . "mod/tidypics/pages/lists/mostviewedimagesthismonth.php");
	add_submenu_item(elgg_echo('tidypics:mostviewedlastmonth'), $CONFIG->url . "mod/tidypics/pages/lists/mostviewedimageslastmonth.php");
	add_submenu_item(elgg_echo('tidypics:mostviewedtoday'), $CONFIG->url . "mod/tidypics/pages/lists/mostviewedimagestoday.php");
	add_submenu_item(elgg_echo('tidypics:mostcommented'), $CONFIG->url . "mod/tidypics/pages/lists/mostcommentedimages.php");
	add_submenu_item(elgg_echo('tidypics:mostcommentedthismonth'), $CONFIG->url . "mod/tidypics/pages/lists/mostcommentedimagesthismonth.php");
	add_submenu_item(elgg_echo('tidypics:mostcommentedtoday'), $CONFIG->url . "mod/tidypics/pages/lists/mostcommentedimagestoday.php");
	add_submenu_item(elgg_echo('tidypics:recentlycommented'), $CONFIG->wwwroot . 'pg/photos/recentlycommented/');
}
