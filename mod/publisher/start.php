<?php
/* MINDS.ORG Publisher plugin
 */

function publisher_init() {
	
	//Register site wide menu
	elgg_register_menu_item('site', array(
			'name' => elgg_echo('publisher:title'),
			'href' =>  elgg_get_site_url() . "stream/",
			'text' =>  elgg_echo('publisher:title'),
		));
		
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('stream','publisher_page_handler');	
	
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
				include(dirname(__FILE__) . "/index.php");
				return true;
		
		}
	} else {
		include(dirname(__FILE__) . "/index.php");
		return true;
	}
	
	return false;
}


register_elgg_event_handler('init','system','publisher_init');

?>
