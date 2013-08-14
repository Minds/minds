<?php

elgg_load_library('archive:kaltura');

//the page owner
$owner = get_user($vars['entity']->owner_guid);


//the number of files to display
$limit = (int) $vars['entity']->num_display;
if (!$limit)
	$limit = 1;
//the number of files to display
$offset = max((int) $vars['entity']->start_display - 1, 0);

$body = elgg_list_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'container_guid' => $owner->getGUID(), 'order_by' => "time_created DESC", 'limit' => $limit, 'offset' => $offset, 'full_view'=>false));
echo $body;
