<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		global $CONFIG;

	// Get the current page's owner
		$page_owner = elgg_get_page_owner_entity();

		if ($page_owner === false || is_null($page_owner)) {
			if(!elgg_is_logged_in()) forward($GLOBAL->wwwroot . 'mod/kaltura_video/everyone.php');
			$page_owner = $_SESSION['user'];
			elgg_set_page_owner_guid($_SESSION['guid']);
		}

	//set blog title
		if($page_owner == $_SESSION['user']){
			$area1 = elgg_view_title(elgg_echo('kalturavideo:label:myvideos'));
		}else{
			$area1 = elgg_view_title(sprintf(elgg_echo('kalturavideo:user'),$page_owner->name));
		}

		// access check for closed groups
		group_gatekeeper();

		$limit = get_input("limit", 10);
		$offset = get_input("offset", 0);

		$area1 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'container_guid' => elgg_get_page_owner_guid(), 'limit' => $limit, 'offset' => $offset));


		// Get categories, if they're installed
		$area2 = elgg_view('kaltura/categorylist',array('baseurl' => $CONFIG->wwwroot . 'search/?subtype=kaltura_video&owner_guid='.$page_owner->guid.'&tagtype=universal_categories&tag=','subtype' => 'kaltura_video', 'owner_guid' => $page_owner->guid));

	// Display them in the page
        $body = elgg_view_layout("two_column_left_sidebar", array('content'=> $area1, 'sidebar'=> $area2));

	// Display page
		echo elgg_view_page(sprintf(elgg_echo('kalturavideo:user'),$page_owner->name),$body);

?>
