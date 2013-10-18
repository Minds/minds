<?php

elgg_load_library('archive:kaltura');

//the page owner
$owner = elgg_get_page_owner_entity();

//the number of files to display
$limit = (int) $vars['entity']->num_display;
if (!$limit)
	$limit = 1;
//the number of files to display

$offset = '';

$body = elgg_list_entities(array('type' => 'object', 'subtypes' => array('archive'), 'owner_guid' => $owner->getGUID(), 'limit' => $limit, 'offset' => $offset, 'full_view'=>false));
echo $body;
