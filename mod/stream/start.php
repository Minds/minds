<?php
/* MINDS.ORG Stream Plugin
 * @author Mark Harding (mark@minds.com)
 */

function stream_init() {
	
	//Register site wide menu
	elgg_register_menu_item('site', array(
			'name' => elgg_echo('stream:title'),
			'href' =>  elgg_get_site_url() . "stream/",
			'title' =>  elgg_echo('stream:title'),
			'text' => '&#58277;',
		));
		
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('stream','publisher_page_handler');	
	
	elgg_extend_view('css/elgg','stream/css');
	
	//register the widget for people profiles.
	elgg_register_widget_type('stream', elgg_echo("stream"), elgg_echo("stream:desc"), "profile");
			
}

//Publisher page hander

function publisher_page_handler($page) {
	global $CONFIG;

	
	if (isset($page[0])) {
		switch($page[0]) {
			case 'all':
				include(dirname(__FILE__) . "/everyone.php");
				return true;
				break;
			case 'show':
				set_input('videopost',$page[1]);
				include(dirname(__FILE__) . "/show.php");
				return true;
				break;
			
			default:
				include(dirname(__FILE__) . "/pages/index.php");
				return true;
		
		}
	} else {
		include(dirname(__FILE__) . "/pages/index.php");
		return true;
	}
	
	return false;
}


register_elgg_event_handler('init','system','stream_init');

?>
