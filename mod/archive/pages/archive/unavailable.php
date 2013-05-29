<?php

/**
 * UNAVAILABLE
 */

$sidebar = elgg_view('archive/sidebar');
/*
		// Get categories, if they're installed
		global $CONFIG;
		$area3 = elgg_view('kaltura/categorylist',array('baseurl' => $CONFIG->wwwroot . 'search/?subtype=kaltura_video&tagtype=universal_categories&tag=','subtype' => 'kaltura_video'));
*/
$body = elgg_view_layout(	"content", array(
												'content' => '<h1> Sorry! We are upgrading and videos are unavailable.</h1><br/> <h1>Please check back later! </h1>', 
												'sidebar' => $sidebar, 
												//'title' => elgg_echo('archive:all'),
											));

	// Display page
echo elgg_view_page(elgg_echo('Sorry... video not available'),$body);

?>
