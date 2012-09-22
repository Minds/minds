<?php
/**
 * Minds
 *
 * @package Minds
 * @author Kramnorth (Mark Harding)
 *
 */

function minds_init(){
											
	elgg_register_simplecache_view('minds');	
	
	elgg_register_event_handler('pagesetup', 'system', 'minds_pagesetup');
	
	elgg_register_page_handler('news', 'minds_news_page_handler');
		
 	elgg_extend_view('page/elements/head','minds/meta');
	
	elgg_extend_view('page/elements/head','minds/analytics', 90000); //such a large number so it is always at the bottom
	
	elgg_extend_view('register/extend', 'minds/register_extend', 500);
	
	//put the quota in account statistics
	elgg_extend_view('core/settings/statistics', 'minds/quota/statistics', 500);

	//register our own css files
	$url = elgg_get_simplecache_url('css', 'minds');
	elgg_register_css('minds.default', $url);	
	
	//register our own js files
	$minds_js = elgg_get_simplecache_url('js', 'minds');
	elgg_register_js('minds.js', $minds_js);
	
	//register inline js player
	$uiVideoInline = elgg_get_simplecache_url('js', 'uiVideoInline');
	elgg_register_js('uiVideoInline', $uiVideoInline);
	
	elgg_unregister_js('jquery');
	elgg_register_js('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 'head');
	elgg_load_js('jquery');
		
	//set the custom index
	elgg_register_plugin_hook_handler('index', 'system','minds_index');
	//make sure users agree to terms
	elgg_register_plugin_hook_handler('action', 'register', 'minds_register_hook');
	
	//add an infinite rather than buttons
	elgg_extend_view('navigation/pagination', 'minds/navigation');
	elgg_register_ajax_view('page/components/ajax_list');
	
	elgg_register_plugin_hook_handler('register', 'menu:river', 'minds_river_menu_setup');
	
	//setup the tracking of user quota - on a file upload, increment, on delete, decrement
	elgg_register_event_handler('create', 'object', 'minds_quota_increment');
	elgg_register_event_handler('delete', 'object', 'minds_quota_decrement');
	
	$actionspath = elgg_get_plugins_path() . "minds/actions/river";
	elgg_register_action("minds/river/delete", "$actionspath/delete.php");
}

function minds_index($hook, $type, $return, $params) {
	if ($return == true) {
		// another hook has already replaced the front page
		return $return;
	}
	
	if(!include_once(dirname(__FILE__) . '/pages/index.php')){
		return false;
	}
	
	return true;
}

/**
 * Page handler for news
 *
 * @param array $page
 * @return bool
 * @access private
 */
function minds_news_page_handler($page) {
	global $CONFIG;

	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make a URL segment available in page handler script
	$page_type = elgg_extract(0, $page, 'friends');
	$page_type = preg_replace('[\W]', '', $page_type);
	if ($page_type == 'owner') {
		$page_type = 'mine';
	}
	set_input('page_type', $page_type);

	// content filter code here
	$entity_type = '';
	$entity_subtype = '';

	require_once("pages/river.php");
	return true;
}

function minds_register_hook()
{
	if (get_input('terms',false) != 'true') {
		register_error(elgg_echo('minds:register:terms:failed'));
		forward(REFERER);
	}
}


function minds_pagesetup(){
	$user = elgg_get_logged_in_user_entity();
	//Top Bar Menu
	elgg_unregister_menu_item('topbar', 'elgg_logo');
	elgg_unregister_menu_item('topbar', 'administration');
	elgg_unregister_menu_item('topbar', 'friends');
	
	elgg_register_menu_item('topbar', array(
			'name' => 'search',
			'href' => '#',
			'text' => elgg_view('search/header'),
			'priority' => 50,
			'section' => 'alt',
		));
		
	elgg_register_menu_item('topbar', array(
			'name' => 'minds_logo',
			'href' => '/',
			'text' => '<img src=\''. elgg_get_site_url() . 'mod/minds/graphics/minds_logo_transparent.png\' class=\'minds_logo\'>',
			'priority' => 0
		));
	
	//RIGHT MENU	
	//profile
	elgg_unregister_menu_item('topbar', 'profile');
	if($user)
	elgg_register_menu_item('topbar', array(
			'name' => 'profile',
			'href' => '/profile/' . elgg_get_logged_in_user_entity()->username,
			'class'=> 'profile',
			'text' => elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny', array('use_hover'=>false)),
			'priority' => 60,
			'section' => 'alt',
		));
	//settings
	elgg_unregister_menu_item('topbar', 'usersettings');
	if($user)
	elgg_register_menu_item('topbar', array(
			'name' => 'usersettings',
			'href' => '/settings/user/' . $user->username,
			'text' => elgg_view_icon('settings-alt'),
			'priority' => 800,
			'section' => 'alt',
		));
	if(!$user)
	elgg_register_menu_item('topbar', array(
			'name' => 'login',
			'href' => '#',
			'text' => elgg_view('core/account/login_dropdown'),
			'priority' => 900,
			'section' => 'alt',
		));
	
	
	//rename activity news	
	elgg_unregister_menu_item('site', 'activity');
	
	$item = new ElggMenuItem('news', elgg_echo('news'), 'news');
	if($user)
	elgg_register_menu_item('site', $item);
}
		
function minds_quota_increment($event, $object_type, $object) {
	
	$user = elgg_get_logged_in_user_entity();
	
	if(($object->getSubtype() == "file") || ($object->getSubtype() == "image")){
		if($object->size){
			$user->quota_storage = $user->quota_storage + $object->size;
			
			$user->save();
		}
		
	} 
	return;
}

function minds_quota_decrement($event, $object_type, $object) {
	if(($object->getSubtype() == "file") || ($object->getSubtype() == "image")){
		echo $object->size;
	}
	
	if($object->getSubtype() == "kaltura_video"){
		//we need to do kaltura differently because it is a remote uplaod
		require_once(dirname(dirname(__FILE__)) ."/kaltura_video/kaltura/api_client/includes.php");
		
	}
	return;

}


/**
 * Edit the river menu defaults
 */
function minds_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];
		$object = $item->getObjectEntity();
		$subject = $item->getSubjectEntity();
	
		elgg_unregister_menu_item('delete');
		if ($subject->canEdit() || $object->canEdit()) {
			$options = array(
				'name' => 'delete',
				'href' => "action/minds/river/delete?id=$item->id",
				'text' => elgg_view_icon('delete'),
				'title' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm'),
				'is_action' => true,
				'priority' => 200,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Replace urls, hash tags, and @'s by links
 * 
 * @param string $text The text
 * @return string
 */
function minds_filter($text) {
	global $CONFIG;

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
				'/(^|[^\w])@([\p{L}\p{Nd}._]+)/u',
				'$1<a href="' . $CONFIG->wwwroot . 'channel/$2">@$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a href="' . $CONFIG->wwwroot . 'search/?q=$2">#$2</a>',
				$text);

	$text = trim($text);

	return $text;
}

elgg_register_event_handler('init','system','minds_init');		

?>
