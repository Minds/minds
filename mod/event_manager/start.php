<?php
	
	define("EVENT_MANAGER_BASEURL", elgg_get_site_url() . "events");
	
	define("EVENT_MANAGER_FORMAT_DATE_EVENTDAY", 	"Y-m-d");
	
	define("EVENT_MANAGER_SEARCH_LIST_LIMIT", 		10);
	define("EVENT_MANAGER_SEARCH_LIST_MAPS_LIMIT", 	100);
	
	define("EVENT_MANAGER_RELATION_ATTENDING", 				"event_attending");
	define("EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST", 	"event_waitinglist");
	define("EVENT_MANAGER_RELATION_EXHIBITING",				"event_exhibiting");
	define("EVENT_MANAGER_RELATION_ORGANIZING",				"event_organizing");
	define("EVENT_MANAGER_RELATION_PRESENTING", 			"event_presenting");
	define("EVENT_MANAGER_RELATION_INTERESTED", 			"event_interested");
	define("EVENT_MANAGER_RELATION_UNDO", 					"event_undo");

	define("EVENT_MANAGER_RELATION_REGISTRATION_QUESTION", 			"event_registration_questions");
	define("EVENT_MANAGER_RELATION_USER_REGISTERED", 				"event_user_registered");
	define("EVENT_MANAGER_RELATION_SLOT_REGISTRATION", 				"event_slot_registration");
	define("EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST", 	"event_slot_registration_waitinglist");
		
	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/run_once.php"); 
	require_once(dirname(__FILE__) . "/lib/hooks.php");
	require_once(dirname(__FILE__) . "/lib/events.php");

	function event_manager_init() {
		// Register subtype
		run_function_once('event_manager_run_once_subtypes');
		
		// Register entity_type for search
		elgg_register_entity_type('object', Event::SUBTYPE);
		
		elgg_extend_view("css/elgg", "event_manager/css/site");
		
		elgg_extend_view("js/elgg", "event_manager/js/site");
		elgg_extend_view("page/elements/head", "event_manager/metatags");
		
		elgg_register_page_handler("events", "event_manager_page_handler");
		
		// add site menu item
		elgg_register_menu_item("site", array(
			"name" => "event_manager",
			"text" => elgg_echo("event_manager:menu:title"), 
			"href" => EVENT_MANAGER_BASEURL
		));
		
		// add group tool option
		add_group_tool_option('event_manager', elgg_echo('groups:enableevents'), true);

		// add to group profile
		elgg_extend_view('groups/tool_latest', 'event_manager/group_module');
		
		// add widgets
		elgg_register_widget_type("events", elgg_echo("event_manager:widgets:events:title"), elgg_echo("event_manager:widgets:events:description"), "index,dashboard,profile,groups");
		
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "event_manager_widget_events_url");
		
		// register js libraries
		$em_maps_js = elgg_get_simplecache_url("js", "event_manager/googlemaps");
		elgg_register_js("event_manager.maps.helper", $em_maps_js);
		elgg_register_js("event_manager.maps.base", "//maps.googleapis.com/maps/api/js?key=" . $maps_key . "&sensor=true");
	}
	
	function event_manager_page_handler($page) {
		elgg_load_js("event_manager.maps.base");
		elgg_load_js("event_manager.maps.helper");

		elgg_push_breadcrumb(elgg_echo("event_manager:menu:events"), EVENT_MANAGER_BASEURL);
		
		$include = "/pages/event/list.php";
		if(!empty($page)) {
			switch($page[0]) {
				case "proc":
					if(file_exists(dirname(__FILE__)."/procedures/".$page[1]."/".$page[2].".php")) {
						$include = "/procedures/".$page[1]."/".$page[2].".php";
					} else {
						echo json_encode(array('valid' => 0));
						exit;
					}
					break;
				case "event":
					switch($page[1]) {
						case 'register':
							if(!empty($page[3])) {
								set_input("relation", $page[3]);	
							}
							break;
						case 'file':
							if(!empty($page[3])) {
								set_input("file", $page[3]);	
							}
							break;
						case 'list':
							set_input("owner_guid", $page[2]);
							break;
						case 'new':
							$page[1] = "edit";
							set_input("owner_guid", $page[2]);
					}
				default:
					if(!empty($page[2]) && ($page[1] !== "new")) {
						set_input("guid", $page[2]);
					}
					
					if(file_exists(dirname(__FILE__)."/pages/".$page[0]."/".$page[1].".php")) {
						$include = "/pages/".$page[0]."/".$page[1].".php";
					} else {
						forward(EVENT_MANAGER_BASEURL);
					}			
					break;
			}			
		}
		
		include(dirname(__FILE__) . $include);
		
		return true;
	}

	function event_manager_pagesetup() {
		// @todo check if this can be better
		elgg_load_js("lightbox");
		elgg_load_css("lightbox");
		
		$page_owner = elgg_get_page_owner_entity();
		if($page_owner instanceof ElggGroup){
			if($page_owner->event_manager_enable == "no"){
				elgg_unregister_widget_type("events");
			}
		}
	}

	// register default elgg events
	elgg_register_event_handler("init", "system", "event_manager_init");
	elgg_register_event_handler("pagesetup", "system", "event_manager_pagesetup");
	
	elgg_register_event_handler("update", "object", "event_manager_update_object_handler");
	
	// hooks
	elgg_register_plugin_hook_handler("register", "menu:user_hover", "event_manager_user_hover_menu");
	elgg_register_plugin_hook_handler("register", "menu:entity", "event_manager_entity_menu", 600);
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'event_manager_owner_block_menu');
	
	// actions
	elgg_register_action("event_manager/event/edit",			dirname(__FILE__) . "/actions/event/edit.php");
	elgg_register_action("event_manager/event/delete",			dirname(__FILE__) . "/actions/event/delete.php");
	elgg_register_action("event_manager/event/rsvp",			dirname(__FILE__) . "/actions/event/rsvp.php");
	elgg_register_action("event_manager/event/upload",			dirname(__FILE__) . "/actions/event/upload.php");
	elgg_register_action("event_manager/event/deletefile",		dirname(__FILE__) . "/actions/event/deletefile.php");
	elgg_register_action("event_manager/event/search",			dirname(__FILE__) . "/actions/event/search.php");
	elgg_register_action("event_manager/attendees/export",		dirname(__FILE__) . "/actions/attendees/export.php");
	elgg_register_action("event_manager/slot/edit",				dirname(__FILE__) . "/actions/slot/edit.php");
	elgg_register_action("event_manager/questions/edit",		dirname(__FILE__) . "/actions/registrationform/edit.php");
	elgg_register_action("event_manager/registration/edit",		dirname(__FILE__) . "/actions/registration/edit.php");
	elgg_register_action("event_manager/registration/approve",	dirname(__FILE__) . "/actions/registration/approve.php");
	
	elgg_register_action("event_manager/registration/pdf",		dirname(__FILE__) . "/actions/registration/pdf.php", "public");
	elgg_register_action("event_manager/event/register",		dirname(__FILE__) . "/actions/event/register.php", "public");
	
	elgg_register_action("event_manager/migrate/calender",		dirname(__FILE__) . "/actions/migrate/calender.php", "admin");
	