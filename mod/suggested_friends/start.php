<?php
/**
 * Adapted for Elgg 1.8 by Matt Beckett
 * 
 * Based on people_from_the_neighborhood
 *
 * @author emdagon
 * @link http://community.elgg.org/pg/profile/emdagon
 * @copyright (c) Condiminds 2011
 * @link http://www.condiminds.com/
 * @license GNU General Public License (GPL) version 2
 */

// get our functions
require_once 'lib/functions.php';


// plugin init
function suggested_friends_init() {

	elgg_extend_view('css/elgg', 'suggested_friends/css');

	elgg_register_page_handler('suggested_friends', 'suggested_friends_page_handler');

	elgg_register_widget_type('suggested_friends', elgg_echo('suggested_friends:people:you:may:know'), elgg_echo('suggested_friends:widget:description'), 'dashboard,profile');
}



function suggested_friends_page_handler($page) {

  $friends = $groups = 0;
  switch ($page[0]) {
	case 'friends':
		$friends = 3;
		break;
	case 'groups':
		$groups = 3;
		break;
	default:
		$friends = $groups = 3;
  }

  set_input('friends', $friends);
  set_input('groups', $groups);
	
  if(include('pages/suggested_friends.php')){
    return TRUE;
  }
  
  return FALSE;
}

//
// set up our links and page specific items
function suggested_friends_page_setup(){

  // add to site links
  if(elgg_is_logged_in()){
    $item = new ElggMenuItem('suggested_friends', elgg_echo('suggested_friends:new:people'), elgg_get_site_url() . 'suggested_friends/');
    elgg_register_menu_item('site', $item);
  }
	
  if(elgg_get_context() == "suggested_friends"){
    $all = new ElggMenuItem('suggested_friends_all', elgg_echo('suggested_friends:all'), elgg_get_site_url() . 'suggested_friends/');
    $friends = new ElggMenuItem('suggested_friends_friends', elgg_echo('suggested_friends:friends:only'), elgg_get_site_url() . 'suggested_friends/friends');
    $groups = new ElggMenuItem('suggested_friends_groups', elgg_echo('suggested_friends:groups:only'), elgg_get_site_url() . 'suggested_friends/groups');
    
    elgg_register_menu_item('page', $all);
    elgg_register_menu_item('page', $friends);
    elgg_register_menu_item('page', $groups);
  }
}

elgg_register_event_handler('pagesetup', 'system', 'suggested_friends_page_setup');
elgg_register_event_handler('init', 'system', 'suggested_friends_init');