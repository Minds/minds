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
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");

	// Get the specified blog post
	$post = (int) get_input('videopost');

	// If we can get out the blog post ...
	if ($videopost = get_entity($post)) {

	// Set the page owner
		elgg_set_page_owner_guid($videopost->getOwnerGUID());
		$page_owner = get_entity($videopost->getOwnerGUID());
		
	//set the tags
	$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'kaltura_video');
	$partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');

	$widgetUi = elgg_get_plugin_setting('custom_kdp', 'kaltura_video');
		
	$video_location = $kaltura_server . '/index.php/kwidget/wid/_'.$partnerId.'/uiconf_id/' . $widgetUi . '/entry_id/'. $videopost->kaltura_video_id;
		
	 minds_set_metatags('og:type', 'video.other');
	 minds_set_metatags('og:image', $videopost->kaltura_video_thumbnail);
	 minds_set_metatags('og:title', $videopost->title);
	 minds_set_metatags('og:description', $videopost->description);
	 minds_set_metatags('og:video', $video_location);
	 minds_set_metatags('og:video:width', '1280');
	 minds_set_metatags('og:video:height', '720');

	// Display it
		$area2 = elgg_view("kaltura/view");
	// Set the title appropriately
		$title = sprintf(elgg_echo("kalturavideo:posttitle"),$page_owner->name,$videopost->title);
		$area1 = $videopost->title;

	// Display through the correct canvas area
		$body = elgg_view_layout("content", array(	'filter'=> '', 
													'title' => $area1,
													'content'=> $area2,
													'sidebar' => $area3 
												));

	// If we're not allowed to see the blog post
	} else {

	// Display the 'post not found' page instead
			$body = elgg_view("kaltura/notfound");
			$title = elgg_echo("kalturavideo:notfound");

	}

	// Display page
	echo elgg_view_page($title,$body);

?>
