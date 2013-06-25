<?php
 
function ac130_init() {
    //Extend beauty
	elgg_extend_view('page/elements/elgg','page/elements/header_logo');
	elgg_extend_view('page/elements/elgg','page/elements/sidebar');
	elgg_extend_view('page/layouts/content/elgg','page/layouts/content/header');
	elgg_unregister_menu_item('topbar', 'elgg_logo');


	//Enhance beauty of CSS with our own styles */
	elgg_extend_view('css/elements/elgg','css/elements/layout');
	elgg_extend_view('css/elements/elgg','css/elements/buttons');
	elgg_extend_view('css/elements/elgg','css/elements/icons');
	elgg_extend_view('css/elements/elgg','css/elements/navigation');

	
    // Replace the default index page */
    register_plugin_hook('index','system','new_index');
}
 
function new_index() {
    if (!include_once(dirname(dirname(__FILE__)) . "/ac-130/index.php"))
        return false;
 
    return true;
}
 
// register for the init, system event when our plugin start.php is loaded
elgg_register_event_handler('init','system','ac130_init');
?>