<?php

/**
	*/

class Videoconsultation extends ElggObject {

	protected function initialise_attributes() {
		$ver=explode('.', get_version(true));
		if ($ver[1]>7) parent::initializeAttributes();
		else parent::initialise_attributes();
		$this->attributes['subtype'] = 'videoconsultation';
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	// more customizations here
}


function videoconsultation_init() {

	// Load system configuration
	global $CONFIG;

	// Set up menu for logged in users
	if (isloggedin()) {

		$ver=explode('.', get_version(true));			
		if ($ver[1]>7) {

    	elgg_register_library('elgg:videoconsultation', $CONFIG->pluginspath . "videoconsultation/lib/videoconsultation.php");

    	// menus
    	elgg_register_menu_item('site', array(
    		'name' => 'videoconsultation',
    		'text' => elgg_echo('videoconsultation'),
    		'href' => 'videoconsultation/all'
    	));
    }
		else add_menu(elgg_echo('videoconsultation'), $CONFIG->wwwroot."pg/videoconsultation/all");
	}

	// Extend system CSS with our own styles
	elgg_extend_view('css','videoconsultation/css');

	//add a widget
	add_widget_type('videoconsultationrooms',"Video Consultation Rooms","Video Consultation Rooms");

	//register a page
	register_page_handler('videoconsultation', 'videoconsultation_page_handler');

	// Register a URL handler for videoconsultation rooms
	register_entity_url_handler('videoconsultation_url','object','videoconsultation');

	register_entity_type('object', 'videoconsultation');
	add_subtype('object', 'videoconsultation', 'Videoconsultation');
}


//page setup
function videoconsultation_pagesetup() {

	global $CONFIG;

	//add submenu options
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
  	if (elgg_get_context() == "videoconsultation") {
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in()) {
				$url =  "videoconsultation/add";
				$item = new ElggMenuItem('videoconsultation:create', elgg_echo('videoconsultation:create'), $url);
				elgg_register_menu_item('page', $item);
			}
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in() && elgg_is_admin_logged_in()) {
				$url =  "videoconsultation/settings";
				$item = new ElggMenuItem('videoconsultation:setting', elgg_echo('videoconsultation:setting'), $url);
				elgg_register_menu_item('page', $item);
			}
    }
  }
  else
  {
  	if (get_context() == "videoconsultation") {
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
				add_submenu_item(elgg_echo('videoconsultation:create'),$CONFIG->wwwroot."pg/videoconsultation/add");
				add_submenu_item(elgg_echo('videoconsultation:rooms'),$CONFIG->wwwroot."pg/videoconsultation/all");
			}
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin() && $_SESSION['guid'] == '2') {
				//if (user_flag_get("admin",$_SESSION['guid'])) {
				add_submenu_item(elgg_echo('videoconsultation:setting'),$CONFIG->wwwroot."pg/videoconsultation/settings");
			}
  	}
  }
}

function videoconsultation_page_handler($page) {

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
		if (isloggedin()) elgg_load_library('elgg:videoconsultation');

	  elgg_push_breadcrumb(elgg_echo('videoconsultation'), 'videoconsultation/all');
  }

	$pages = dirname(__FILE__) . '/pages/videoconsultation';

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
		// The first component of a videoconsultation URL is the room name    
		set_input('roomname',$page[0]);
		include "$pages/videoconsultation.php";
	}
	return true;
}

/**
		* Populates the ->getUrl() method for videoconsultation objects
		*/
function videoconsultation_url($consroom) {
	global $CONFIG;
	$room = $consroom->room;
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	return $CONFIG->url . "videoconsultation/" . urlencode($room);
	else return $CONFIG->url . "pg/videoconsultation/" . urlencode($room);
}


// Make sure the videoconsultation initialisation function is called on initialisation
register_elgg_event_handler('init','system','videoconsultation_init');
register_elgg_event_handler('pagesetup','system','videoconsultation_pagesetup');

// Register actions
global $CONFIG;
register_action("videoconsultation/create",false,$CONFIG->pluginspath . "videoconsultation/actions/create.php");
register_action("videoconsultation/edit",false,$CONFIG->pluginspath . "videoconsultation/actions/edit.php");
register_action('videoconsultation/delete',false,$CONFIG->pluginspath . "videoconsultation/actions/delete.php");
register_action('videoconsultation/setting',false,$CONFIG->pluginspath . "videoconsultation/actions/setting.php");
?>
