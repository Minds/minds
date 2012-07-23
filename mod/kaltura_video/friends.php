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
		define('everyonekaltura_video','true');
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		if (!($page_owner instanceof ElggEntity)) forward();

	//set the title
        if($page_owner == $_SESSION['user']){
			$area2 = elgg_view_title(elgg_echo('kalturavideo:label:friendsvideos'));
		}else{
			$area2 = elgg_view_title(sprintf(elgg_echo('kalturavideo:user:friends'),$page_owner->name));
		}

	// Get a list of blog posts
		$area2 .= list_user_friends_objects($page_owner->getGUID(),'kaltura_video',10,false);

	// Get categories, if they're installed
		global $CONFIG;
		$area3 = elgg_view('kaltura/categorylist',array('baseurl' => $CONFIG->wwwroot . 'search/?subtype=kaltura_video&owner_guid='.$page_owner->guid.'&friends='.$page_owner->guid.'&tagtype=universal_categories&tag=','subtype' => 'kaltura_video'));

	// Display them in the page
        $body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);

	// Display page
		page_draw(elgg_echo('kalturavideo:label:friendsvideos'),$body);

?>
