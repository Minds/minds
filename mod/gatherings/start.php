<?php
/**
 * Minds gatherings.
 * 
 * @package Minds
 * @subpackage gatherings
 * @author Mark Harding (mark@minds.com)
 * 
 */

elgg_register_event_handler('init','system','gatherings_init');
	
/**
 * Initialize the gathering plugin.
 */
function gatherings_init(){

	add_subtype("object", "gathering", "MindsGathering");  
		
	elgg_register_library('bblr', elgg_get_plugins_path() . 'gatherings/vendors/bblr-php-sdk/bblr.php');
	elgg_register_library('minds:gatherings', elgg_get_plugins_path() . 'gatherings/lib/gatherings.php');
	
	// Register a url handler for the new object
	elgg_register_entity_url_handler('object', 'gathering', 'gatherings_url');
	
	elgg_extend_view('page/elements/foot', 'gatherings/bar');
	elgg_extend_view('css/elgg', 'gatherings/css');

	elgg_extend_view('js/elgg', 'js/gatherings/live');
	
//	elgg_register_js('gatherings', elgg_get_simplecache_url('js', 'gatherings/live'), 'footer', 601);
	//elgg_load_js('gatherings');
	elgg_register_js('portal', elgg_get_site_url() . 'mod/gatherings/vendors/portal.js');
	elgg_load_js('portal');
	
	elgg_register_js('swfobject', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/swfobject.js', 'footer',599);
	elgg_load_js('swfobject');
	//elgg_register_js('bblr', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/br_api.js', 'footer',600);
	//elgg_register_js('bblr', 'https://api.babelroom.com/cdn/v1/br_api.full.min.js', 'footer',600);
	//elgg_load_js('bblr');
	//elgg_register_js('webRTC', elgg_get_site_url() . 'mod/gatherings/vendors/webRTC/webRTC.js');
	//elgg_load_js('webRTC');
	
	elgg_register_js('wraprtc', elgg_get_site_url() . 'mod/gatherings/vendors/bblr-js/wraprtc.js', 'footer', 700);
	elgg_load_js('wraprtc');
		
	//add a tab in site menu
	//$item = new ElggMenuItem('webinar', elgg_echo('gatherings:menu:site'), 'gatherings/all');
	if(elgg_is_logged_in()){

	/*	elgg_register_menu_item('site', array(	'name'=>'gathering',
							'title'=>elgg_echo('gatherings:menu:site'),
							'href'=>'gatherings/all',
							'text' => '&#58277;',
							'priority' => 150	
					));*/
	}
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('gatherings','gatherings_page_handler');
		
	// Register some actions
	$action_base = elgg_get_plugins_path() . 'gatherings/actions/gatherings';
	elgg_register_action("gatherings/join", "$action_base/join.php");
	elgg_register_action("gatherings/save", "$action_base/save.php");
	elgg_register_action("gatherings/delete", "$action_base/delete.php");

	// Extend the main css view
	elgg_extend_view('css','webinar/css');
		
	//register_elgg_event_handler('pagesetup','system','gatherings_pagesetup');
	// Register for notifications
	//register_notification_object('object', 'webinar', elgg_echo('gatherings:notify:new'));
		
	// add checkbox on group edit page to activate webinar
	//add_group_tool_option('webinar',elgg_echo('gatherings:enable'),false);
	//elgg_extend_view('groups/tool_latest', 'webinar/group_module');
	// owner_block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'gatherings_handler_menu_owner_block');
	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'gatherings_handler_menu_entity');
		
	// Listen to notification events and supply a more useful message
//	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'gatherings_handler_notify_message');
		
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'gatherings_handler_permissions_check');
		
	//intercept event_calendar notification because event that type is webinar are create by webinar object
//	elgg_register_plugin_hook_handler('object:notifications','object','gatherings_handler_notifications_intercept');
		
	//on event create attendee relation ship, call add_river
//	elgg_register_event_handler('create','attendee','gatherings_handler_relationship_river');

}

/**
 * Gatherings page handler
 * @param array $page
 * @return bool
 */
function gatherings_page_handler($page){
	
	elgg_load_library('minds:gatherings');

	elgg_push_breadcrumb(elgg_echo('gatherings:webinars'), "gatherings/all");
		
	if (!isset($page[0])) {
		$page[0] = 'all';
	}
	
	switch($page[0]) {
		case 'all':
		case "owner":
		case 'friends':
		case "group":
			$params = gatherings_get_page_content_list($page);
			break;
		case "view":
			if (isset($page[1])) {
				$params = gatherings_get_page_content_view($page[1]);
			}else{
				return false;
			}
			break;
		case "join":
			$gathering = get_entity($page[1]);
			if($gathering instanceof ElggUser){
				$user = $gathering;
				$gathering = new MindsGathering();
				if(isset($user->bblr_id)){
					//join an existing, because you are probably not the user
					$gathering->bblr_id = $user->bblr_id;
					
				} elseif($user->guid == elgg_get_logged_in_user_guid()){
					//create new for yourself, because you have probably just logged in.
					$gathering->title = $user->name;
					$gahtering->description = 'User gathering';
					$user->bblr_id = $gathering->create();
					$user->save();
				}
			}
			$return = array();
			$return['token'] = $gathering->join(elgg_get_logged_in_user_entity());
			$return['cid'] = (int) $gathering->bblr_id;
			echo json_encode($return);
			exit;
			break;
		case "add":
		case "edit":
			gatekeeper();
			if (isset($page[1])) {
				$params = gatherings_get_page_content_edit($page_type, $page[1]);
				$body = elgg_view_layout('content', $params);
			}else{
				return false;
			}
			break;
		default:
	    	return false;
	    	break;
		}
		
		if(!$body)	
		$body = elgg_view_layout('gallery', $params);
		
		echo elgg_view_page($params['title'], $body);
		return true;
	}
	
	
	
function gatherings_handler_permissions_check($hook, $entity_type, $returnvalue, $params) {
	if (isset($params)) {
		$entity = $params['entity'];
		$user = $params['user'];
		if ($entity && $entity instanceof ElggWebinar &&  $user && $user instanceof ElggUser) {
			if($entity->getOwnerGUID() == $user->getGUID()){
				$returnvalue = true;
			}else{
				$returnvalue = false;
			}
		}
	}
	return $returnvalue;
}

function gatherings_url($entity){
	global $CONFIG;
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return $CONFIG->url . "gatherings/view/" . $entity->getGUID() . "/" . $title;
}
