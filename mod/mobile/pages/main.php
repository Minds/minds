<?php

	/**
	 * main page by mark harding
	 * 
	 */


//featured
$limit = get_input('limit', 12);
$offset = get_input('offset', 0); 
$entities = minds_get_featured('', $limit, 'entities',$offset); 
$featured = elgg_view_entity_list($entities, array('full_view'=>false), $offset, $limit, false, false, true);

if (!elgg_is_logged_in()) {

$params = elgg_view('core/account/login_box');

}

$params .= $featured;

$body = elgg_view_layout('one_column', $params);



echo elgg_view_page($title,$body);
	
?>
