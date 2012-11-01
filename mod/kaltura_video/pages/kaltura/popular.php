<?php
/**
 * Shows the videos that have the most up votes
 * For now, time does not matter
 */



$limit = get_input("limit", 10);
$offset = get_input("offset", 0);
$filter = get_input("filter", "all");

if($filter == 'media')
$subtypes = 'kaltura_video';
elseif ($filter == 'images')
$subtypes = 'album';
elseif ($filter == 'files')
$subtypes = 'file';
else
$subtypes = array('kaltura_video', 'album', 'file');

$options = array('annotation_names' => 'thumbs:up', 'types' => 'object', 'subtypes' => $subtypes, 'limit' => $limit, 'offset' => $offset,);
$entities = elgg_get_entities_from_annotation_calculation($options);
$vars['entities'] = $entities;

$content = elgg_view('minds/tiles',$vars);

elgg_register_menu_item('title', array(
		'name' => 'record',
		'text' => elgg_echo('kalturavideo:label:newvideocam'),
		'href' => "#kaltura_create",
		'link_class' => 'elgg-button elgg-button-action',
	));
elgg_register_menu_item('title', array(
		'name' => 'upload',
		'text' => elgg_echo('kalturavideo:label:upload'),
		'href' => "/upload",
		'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
	));

// get tagcloud
// $area3 = "This will be a tagcloud for all blog posts";

// Get categories, if they're installed
global $CONFIG;
$area3 = elgg_view('kaltura/categorylist',array('baseurl' => $CONFIG->wwwroot . 'search/?subtype=kaltura_video&tagtype=universal_categories&tag=','subtype' => 'kaltura_video'));

$body = elgg_view_layout("tiles", array(
					'content' => $content, 
					'sidebar' => false,
					'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
					'title' => elgg_echo('archive:top')
					));

// Display page
echo elgg_view_page(elgg_echo('kalturavideo:label:allvideos'),$body);

?>
