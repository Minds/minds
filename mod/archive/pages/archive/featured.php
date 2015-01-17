<?php

// Get the current page's owner
$page_owner = elgg_get_logged_in_user_guid();
elgg_set_page_owner_guid($page_owner);
elgg_set_context('featured');
$limit = get_input("limit", 12);
$offset = get_input("offset", 0);

$masonry = true;
switch(get_input('subtype', 'default')){
	case 'video':
		$key = 'object:video:featured';
		$masonry = false;
		break;
	case 'albums':
		$key = 'object:image:featured';
		break;
	default:
		$key = 'object:archive:featured';
}

$guids = Minds\Core\data\indexes::fetch($key, array('offset'=>$offset, 'limit'=>$limit, 'reversed'=>true));

if($guids){
	$entities = elgg_get_entities(array(	
		'guids'=> $guids,
		'full_view' => FALSE,
		'limit' => 12,
		//'archive_view' => TRUE
	));
	usort($entities, function($a, $b){
            //return strcmp($b->featured_id, $a->featured_id);
		if ((int)$a->featured_id == (int) $b->featured_id) { //imposisble
      	 	   return 0;
       		 }
		return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
	});
	$content = elgg_view_entity_list($entities, array('full_view'=>FALSE, 'load-next'=>end($entities)->featured_id));
} else {
	$content = '';
}
if(!get_input('ajax'))
elgg_set_context('archive');
$body = elgg_view_layout("gallery", array(
				'content' => $content, 
				'title' => elgg_echo('archive'),
				'filter_override' => elgg_view('page/layouts/content/archive_filter', array('filter_context'=>'featured'))
			));


echo elgg_view_page('',$body);

