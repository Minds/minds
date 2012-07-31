<?php

/**
 * Activity River privacy
 * modifies the riverdashboard so that it
 * only shows frinds or mine - no relationships, profile changes etc
 * based on 1.8.1 Elgg code
 * @author Steve Aquila
 * iconmatrix.com
 */

elgg_register_event_handler('init', 'system', 'private_river_init');


function private_river_init() {
	if (elgg_is_admin_logged_in()) {} else { 
    if (elgg_get_context() == 'activity') {
        elgg_unregister_menu_item('all', 'filter');
    }
    elgg_unregister_page_handler('activity');
    elgg_register_page_handler('activity', 'private_river_page_handler');
}}

function private_river_page_handler($page) {
    $page_type = elgg_extract(0, $page, false);
    if (!$page_type) {
        forward('activity/owner');
    }
    if ($page_type == 'owner') {
        $page_type = 'mine';
    }
    set_input('page_type', $page_type);

    // content filter code here
    $entity_type = '';
    $entity_subtype = '';

    global $CONFIG;
    require_once("{$CONFIG->path}pages/river.php");
}
?>
