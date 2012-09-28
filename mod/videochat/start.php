<?php

/**
	*/

class Videochat extends ElggObject {

	protected function initialise_attributes() {
		$ver=explode('.', get_version(true));
		if ($ver[1]>7) parent::initializeAttributes();
		else parent::initialise_attributes();
		$this->attributes['subtype'] = 'videochat';
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	// more customizations here
}

// Make sure the videochat initialisation function is called on initialisation
elgg_register_event_handler('init','system','videochat_init');

function videochat_init() {

	// Load system configuration
	global $CONFIG;

	// Set up menu for logged in users
	if (isloggedin()) {

		$ver=explode('.', get_version(true));			
		if ($ver[1]>7) {

    	elgg_register_library('elgg:videochat', $CONFIG->pluginspath . "videochat/lib/videochat.php");

    	// menus
    	elgg_register_menu_item('site', array(
    		'name' => 'videochat',
    		'text' => elgg_echo('videochat'),
    		'href' => 'videochat/all'
    	));
    }
		else add_menu(elgg_echo('videochat'), $CONFIG->wwwroot."pg/videochat/all");
	}

	// Extend system CSS with our own styles
	elgg_extend_view('css','videochat/css');

	//add a widget
	add_widget_type('videochatrooms',"Video Chat Rooms","Video Chat Rooms");

	//register a page
	elgg_register_page_handler('videochat', 'videochat_page_handler');

	// Register a URL handler for videochat rooms
	elgg_register_entity_url_handler('videochat_url','object','videochat');

	elgg_register_entity_type('object', 'videochat');
	
	elgg_register_event_handler('pagesetup','system','videochat_pagesetup');

	elgg_register_action("videochat/create",  elgg_get_plugins_path() . "videochat/actions/create.php");
	elgg_register_action("videochat/edit", elgg_get_plugins_path() . "videochat/actions/edit.php");
	elgg_register_action('videochat/delete', elgg_get_plugins_path() . "videochat/actions/delete.php");
	elgg_register_action('videochat/setting', elgg_get_plugins_path() . "videochat/actions/setting.php");
}


//page setup
function videochat_pagesetup() {

	global $CONFIG;

	//add submenu options
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
  	if (elgg_get_context() == "videochat") {
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in()) {
				$url =  "videochat/add";
				$item = new ElggMenuItem('videochat:create', elgg_echo('videochat:create'), $url);
				elgg_register_menu_item('page', $item);
			}
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in() && elgg_is_admin_logged_in()) {
				$url =  "videochat/settings";
				$item = new ElggMenuItem('videochat:setting', elgg_echo('videochat:setting'), $url);
				elgg_register_menu_item('page', $item);
			}
    }
  }
  else
  {
  	if (get_context() == "videochat") {
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
				add_submenu_item(elgg_echo('videochat:create'),$CONFIG->wwwroot."pg/videochat/add");
				add_submenu_item(elgg_echo('videochat:rooms'),$CONFIG->wwwroot."pg/videochat/all");
			}
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin() && $_SESSION['guid'] == '2') {
				//if (user_flag_get("admin",$_SESSION['guid'])) {
				add_submenu_item(elgg_echo('videochat:setting'),$CONFIG->wwwroot."pg/videochat/settings");
			}
  	}
  }
}

function videochat_page_handler($page) {

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
		if (isloggedin()) elgg_load_library('elgg:videochat');

	  elgg_push_breadcrumb(elgg_echo('videochat'), 'videochat/all');
  }

	$pages = dirname(__FILE__) . '/pages/videochat';

	switch ($page[0]) {
	case 'all':
		include "$pages/all.php";
		break;
	case 'friends':
    include "$pages/friends.php";
		break;
	case 'owner':
    include "$pages/owner.php";
		break;
	case 'add':
		gatekeeper();		
    include "$pages/add.php";
		break;
	case 'edit':
		gatekeeper();
		set_input('guid', $page[1]);
		include "$pages/edit.php";
		break;
	case 'settings':
	  admin_gatekeeper();
		include "$pages/admin.php";
		break;
	case 'group':
		group_gatekeeper();
		include "$pages/owner.php";
		break;
  // vwtemplate
	case 'createrooms':
		gatekeeper();
		set_input('roomname', $_GET [roomname] );
    include "$pages/add.php";
		break;
	default:
		// The first component of a videochat URL is the room name    
		set_input('roomname',$page[0]);
		include "$pages/videochat.php";
	}
	return true;
}

/**
		* Populates the ->getUrl() method for videochat objects
		*/
function videochat_url($chatroom) {
	global $CONFIG;
	$room = $chatroom->room;
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	return $CONFIG->url . "videochat/" . urlencode($room);
	else return $CONFIG->url . "pg/videochat/" . urlencode($room);
}

?>
