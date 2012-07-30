<?php

/**
 * Most recently viewed images - world view only right now
 *
 */

// Load Elgg engine
include_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php";

// world view - set page owner to logged in user
if (isloggedin()) {
	set_page_owner(get_loggedin_userid());
}

// allow other plugins to override the slideshow
$slideshow_link = trigger_plugin_hook('tp_slideshow', 'album', array(), null);
if ($slideshow_link) {
	add_submenu_item(elgg_echo('album:slideshow'),
			$slideshow_link,
			'photos' );
}


global $CONFIG;
$prefix = $CONFIG->dbprefix;
$max_limit = 200; //get extra because you'll have multiple views per image in the result set
$max = 16; //controls how many actually show on screen

//this works but is wildly inefficient
//$annotations = get_annotations(0, "object", "image", "tp_view", "", "", 5000);

$sql = "SELECT distinct ent.guid, ann1.time_created
			FROM " . $prefix . "entities ent
			INNER JOIN " . $prefix . "entity_subtypes sub ON ent.subtype = sub.id
			AND sub.subtype = 'image'
			INNER JOIN " . $prefix . "annotations ann1 ON ann1.entity_guid = ent.guid
			INNER JOIN " . $prefix . "metastrings ms ON ms.id = ann1.name_id
			AND ms.string = 'tp_view'
			ORDER BY ann1.id DESC
			LIMIT $max_limit";

$result = get_data($sql);

$entities = array();
foreach ($result as $entity) {
	if (!$entities[$entity->guid]) {
		$entities[$entity->guid] = get_entity($entity->guid);
	}
	if (count($entities) >= $max) {
		break;
	}
}

$title = elgg_echo("tidypics:recentlyviewed");
$area2 = elgg_view_title($title);

// grab the html to display the images
$images = tp_view_entity_list($entities, $max, 0, $max, false);

// this view takes care of the title on the main column and the content wrapper
$area2 = elgg_view('tidypics/content_wrapper', array('title' => $title, 'content' => $images,));
if (empty($area2)) {
	$area2 = $images;
}

$body = elgg_view_layout('two_column_left_sidebar', '', $area2);
page_draw($title, $body);
