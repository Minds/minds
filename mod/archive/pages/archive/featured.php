<?php

// Get the current page's owner
$page_owner = elgg_get_logged_in_user_guid();
elgg_set_page_owner_guid($page_owner);
elgg_set_context('featured');
$limit = get_input("limit", 12);
$offset = get_input("offset", 0);

$guids = minds\core\data\indexes::fetch('object:archive:featured', array('offset'=>$offset, 'limit'=>$limit));

if($guids){
	$content = elgg_list_entities(array(	
		'guids'=> $guids,
		'full_view' => FALSE,
		//'archive_view' => TRUE
	));
} else {
	$content = '';
}

$body = elgg_view_layout("gallery", array(
				'content' => $content, 
				'title' => elgg_echo('archive'),
				'filter_override' => elgg_view('page/layouts/content/archive_filter', array('filter_context'=>'featured'))
			));


echo elgg_view_page('',$body);

