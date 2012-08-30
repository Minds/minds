<?php

/**
	*/

class Livestreaming extends ElggObject {

	protected function initialise_attributes() {
		$ver=explode('.', get_version(true));
		if ($ver[1]>7) parent::initializeAttributes();
		else parent::initialise_attributes();
		$this->attributes['subtype'] = 'livestreaming';
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	// more customizations here
}


function livestreaming_init() {

	// Load system configuration
	global $CONFIG;

	// Set up menu for logged in users
	if (isloggedin()) {

		$ver=explode('.', get_version(true));			
		if ($ver[1]>7) {

    	elgg_register_library('elgg:livestreaming', $CONFIG->pluginspath . "livestreaming/lib/livestreaming.php");

    	// menus
    	/*elgg_register_menu_item('site', array(
    		'name' => 'livestreaming',
    		'text' => elgg_echo('livestreaming'),
    		'href' => 'livestreaming/all'
    	));*/
    }
		else add_menu(elgg_echo('livestreaming'), $CONFIG->wwwroot."pg/livestreaming/all");
	}

	// Extend system CSS with our own styles
	elgg_extend_view('css','livestreaming/css');

	//add a widget
	add_widget_type('livestreamingrooms',"Live Streaming Rooms","Live Streaming Rooms");

	//register a page
	register_page_handler('livestreaming', 'livestreaming_page_handler');

	// Register a URL handler for livestreaming rooms
	register_entity_url_handler('livestreaming_url','object','livestreaming');

	register_entity_type('object', 'livestreaming');
	add_subtype('object', 'livestreaming', 'Livestreaming');
}


//page setup
function livestreaming_pagesetup() {

	global $CONFIG;

	//add submenu options
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
  	if (elgg_get_context() == "livestreaming") {
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in()) {
				$url =  "livestreaming/add";
				$item = new ElggMenuItem('livestreaming:create', elgg_echo('livestreaming:create'), $url);
				elgg_register_menu_item('page', $item);
			}
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in() && elgg_is_admin_logged_in()) {
				$url =  "livestreaming/settings";
				$item = new ElggMenuItem('livestreaming:setting', elgg_echo('livestreaming:setting'), $url);
				elgg_register_menu_item('page', $item);
			}
    }
  }
  else
  {
  	if (get_context() == "livestreaming") {
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
				add_submenu_item(elgg_echo('livestreaming:create'),$CONFIG->wwwroot."pg/livestreaming/add");
				add_submenu_item(elgg_echo('livestreaming:rooms'),$CONFIG->wwwroot."pg/livestreaming/all");
			}
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin() && $_SESSION['guid'] == '2') {
				//if (user_flag_get("admin",$_SESSION['guid'])) {
				add_submenu_item(elgg_echo('livestreaming:setting'),$CONFIG->wwwroot."pg/livestreaming/settings");
			}
  	}
  }
}

function livestreaming_page_handler($page) {


	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
		if (isloggedin()) elgg_load_library('elgg:livestreaming');

	  elgg_push_breadcrumb(elgg_echo('livestreaming'), 'livestreaming/all');
  }

	$pages = dirname(__FILE__) . '/pages/livestreaming';

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
		set_input('roomname',$page[0]);
		$live = $_GET['live'];
		switch ($live) {
		case '1':
  		include "$pages/live_broadcast.php";
			return true;
			break;
		case '2':
  		include "$pages/channel.php";
			return true;
			break;
		case '3':
  		include "$pages/video.php";
			return true;
			break;
		default:
  		include "$pages/channel.php";
			return true;
		}
	}
}

/**
		* Populates the ->getUrl() method for livestreaming objects
		*/
function livestreaming_url($streamingroom) {
	global $CONFIG;
	$room = $streamingroom->room;

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	return $CONFIG->url . "livestreaming/" . urlencode($room);
	else return $CONFIG->url . "pg/livestreaming/" . urlencode($room);
}


// Make sure the livestreaming initialisation function is called on initialisation
register_elgg_event_handler('init','system','livestreaming_init');
register_elgg_event_handler('pagesetup','system','livestreaming_pagesetup');

// Register actions
global $CONFIG;
register_action("livestreaming/create",false,$CONFIG->pluginspath . "livestreaming/actions/create.php");
register_action("livestreaming/edit",false,$CONFIG->pluginspath . "livestreaming/actions/edit.php");
register_action('livestreaming/delete',false,$CONFIG->pluginspath . "livestreaming/actions/delete.php");
register_action('livestreaming/setting',false,$CONFIG->pluginspath . "livestreaming/actions/setting.php");
?>
