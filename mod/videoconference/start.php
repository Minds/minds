<?php

/**
	* Elgg VideoWhisper Video Conference
	*
	* @package ElggVideoconference
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	* @author VideoWhisper.com
	* @copyright VideoWhisper.com 2009-2010
	* @link http://www.videowhisper.com/
	*/

class Videoconference extends ElggObject {

	protected function initialise_attributes() {
		$ver=explode('.', get_version(true));
		if ($ver[1]>7) parent::initializeAttributes();
		else parent::initialise_attributes();
		$this->attributes['subtype'] = 'videoconference';
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	// more customizations here
}


function videoconference_init() {

	// Load system configuration
	global $CONFIG;

	// Set up menu for logged in users
	if (isloggedin()) {

		$ver=explode('.', get_version(true));			
		if ($ver[1]>7) {

    	elgg_register_library('elgg:videoconference', $CONFIG->pluginspath . "videoconference/lib/videoconference.php");

    	// menus
    	elgg_register_menu_item('site', array(
    		'name' => 'videoconference',
    		'text' => elgg_echo('videoconference'),
    		'href' => 'videoconference/all'
    	));
    }
		else add_menu(elgg_echo('videoconference'), $CONFIG->wwwroot."pg/videoconference/all");
	}

	// Extend system CSS with our own styles
	elgg_extend_view('css','videoconference/css');

	//add a widget
	add_widget_type('videoconferencerooms',"Video Conference Rooms","Video Conference Rooms");

	//register a page
	register_page_handler('videoconference', 'videoconference_page_handler');

	// Register a URL handler for videoconference rooms
	register_entity_url_handler('videoconference_url','object','videoconference');

	register_entity_type('object', 'videoconference');
	add_subtype('object', 'videoconference', 'Videoconference');
}


//page setup
function videoconference_pagesetup() {

	global $CONFIG;

	//add submenu options
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
  	if (elgg_get_context() == "videoconference") {
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in()) {
				$url =  "videoconference/add";
				$item = new ElggMenuItem('videoconference:create', elgg_echo('videoconference:create'), $url);
				elgg_register_menu_item('page', $item);
			}
			if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in() && elgg_is_admin_logged_in()) {
				$url =  "videoconference/settings";
				$item = new ElggMenuItem('videoconference:setting', elgg_echo('videoconference:setting'), $url);
				elgg_register_menu_item('page', $item);
			}
    }
  }
  else
  {
  	if (get_context() == "videoconference") {
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
				add_submenu_item(elgg_echo('videoconference:create'),$CONFIG->wwwroot."pg/videoconference/add");
				add_submenu_item(elgg_echo('videoconference:rooms'),$CONFIG->wwwroot."pg/videoconference/all");
			}
			if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin() && $_SESSION['guid'] == '2') {
				//if (user_flag_get("admin",$_SESSION['guid'])) {
				add_submenu_item(elgg_echo('videoconference:setting'),$CONFIG->wwwroot."pg/videoconference/settings");
			}
  	}
  }
}

function videoconference_page_handler($page) {

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
  {
		if (isloggedin()) elgg_load_library('elgg:videoconference');

	  elgg_push_breadcrumb(elgg_echo('videoconference'), 'videoconference/all');
  }

	$pages = dirname(__FILE__) . '/pages/videoconference';

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
		// The first component of a videoconference URL is the room name    
		set_input('roomname',$page[0]);
		include "$pages/videoconference.php";
	}
	return true;
}

/**
		* Populates the ->getUrl() method for videconference objects
		*/
function videoconference_url($conferenceroom) {
	global $CONFIG;
	$room = $conferenceroom->room;
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	return $CONFIG->url . "videoconference/" . urlencode($room);
	else return $CONFIG->url . "pg/videoconference/" . urlencode($room);
}


// Make sure the videoconference initialisation function is called on initialisation
register_elgg_event_handler('init','system','videoconference_init');
register_elgg_event_handler('pagesetup','system','videoconference_pagesetup');

// Register actions
global $CONFIG;
register_action("videoconference/create",false,$CONFIG->pluginspath . "videoconference/actions/create.php");
register_action("videoconference/edit",false,$CONFIG->pluginspath . "videoconference/actions/edit.php");
register_action('videoconference/delete',false,$CONFIG->pluginspath . "videoconference/actions/delete.php");
register_action('videoconference/setting',false,$CONFIG->pluginspath . "videoconference/actions/setting.php");
?>
