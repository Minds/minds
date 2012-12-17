<?php
/**
 * Shows the videos that have the most up votes
 * For now, time does not matter
 */

$tab = get_input('tab');

$limit = get_input("limit", 24);
$offset = get_input("offset", 0);
$filter = get_input("filter", "all");

if($filter == 'media')
$subtypes = 'kaltura_video';
elseif ($filter == 'images')
$subtypes = array('image', 'album');
elseif ($filter == 'files')
$subtypes = 'file';
else
$subtypes = array('kaltura_video', 'image', 'file');

if($tab == 'popular'){
	$options = array('annotation_names' => 'thumbs:up', 'types' => 'object', 'subtypes' => $subtypes, 'limit' => $limit, 'offset' => $offset,);
	$entities = elgg_get_entities_from_annotation_calculation($options);
	$title = elgg_view('archive/wall/title', array('current'=>'popular')); 
} elseif ($tab == 'mostviewed') {
	$options = array('types' => 'object', 'subtypes' => $subtypes, 'metadata_name_value_pairs'=> array('name' => 'kaltura_video_id','value'=>archive_kaltura_get_most_viewed() ),'limit' => $limit);
	$entities = elgg_get_entities_from_metadata($options);
	$title = elgg_view('archive/wall/title', array('current'=>'mostviewed')); 
} else {
	$options = array('types' => 'object', 'subtypes' => $subtypes, 'metadata_name_value_pairs'=> array('name' => 'featured','value'=>true ),'limit' => $limit);
	$entities = elgg_get_entities_from_metadata($options);
	$title = elgg_view('archive/wall/title', array('current'=>'featured')); 
}

$vars['entities'] = $entities;

$content = elgg_view('minds/tiles',$vars);

if(elgg_is_logged_in()){
	elgg_register_menu_item('title', array(
			'name' => 'upload',
			'text' => elgg_echo('kalturavideo:label:newvideo'),
			'href' => "/archive/upload",
			'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
		));
}

$body = elgg_view_layout("tiles", array(
					'content' => $content, 
					'sidebar' => false,
					'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
					'title' => $title
					));

// Display page
echo elgg_view_page($title,$body);
