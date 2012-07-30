<?php

/**
 * Most viewed images - either for a user or all site
 *
 */

// Load Elgg engine
include_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php";

global $CONFIG;
$prefix = $CONFIG->dbprefix;
$max = 24;

$owner_guid = page_owner();

//$start = microtime(true);
$photos = tp_get_entities_from_annotations_calculate_x(	
		'count',
		'object',
		'image',
		'tp_view',
		'',
		'',
		$owner_guid,
		$max);
//error_log("elgg query is " . (float)(microtime(true) - $start));

//this works but is wildly inefficient
//$annotations = get_annotations(0, "object", "image", "tp_view", "", "", 5000);
/*
	$start = microtime(true);
	$sql = "SELECT ent.guid, count( * ) AS views
			FROM " . $prefix . "entities ent
			INNER JOIN " . $prefix . "entity_subtypes sub ON ent.subtype = sub.id
			AND sub.subtype = 'image'
			INNER JOIN " . $prefix . "annotations ann1 ON ann1.entity_guid = ent.guid AND ann1.owner_guid != ent.owner_guid
			INNER JOIN " . $prefix . "metastrings ms ON ms.id = ann1.name_id
			AND ms.string = 'tp_view'
			GROUP BY ent.guid
			ORDER BY views DESC
			LIMIT $max";
	
	$result = get_data($sql);

	$entities = array();
	foreach($result as $entity) {
		$entities[] = get_entity($entity->guid);
	}
*/
//error_log("custom query is " . (float)(microtime(true) - $start));

// allow other plugins to override the slideshow
$slideshow_link = trigger_plugin_hook('tp_slideshow', 'album', array(), null);
if ($slideshow_link) {
	add_submenu_item(elgg_echo('album:slideshow'),
			$slideshow_link,
			'photos' );
}

if ($owner_guid) {
	if ($owner_guid == get_loggedin_userid()) {
		$title = elgg_echo("tidypics:yourmostviewed");
	} else {
		$title = sprintf(elgg_echo("tidypics:friendmostviewed"), page_owner_entity()->name);
	}
} else {
	// world view - set page owner to logged in user
	if (isloggedin()) {
		set_page_owner(get_loggedin_userid());
	}

	$title = elgg_echo("tidypics:mostviewed");
}
$area2 = elgg_view_title($title);

// grab the html to display the images
$content = tp_view_entity_list($photos, $max, 0, $max, false);

// this view takes care of the title on the main column and the content wrapper
$area2 = elgg_view('tidypics/content_wrapper', array('title' => $title, 'content' => $content,));
if (empty($area2)) {
	$area2 = $content;
}

$body = elgg_view_layout('two_column_left_sidebar', '', $area2);
page_draw($title, $body);
