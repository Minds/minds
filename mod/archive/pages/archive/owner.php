<?php

$limit = get_input("limit", 12);
$offset = get_input("offset", 0);
$username = get_input("username", elgg_get_logged_in_user_entity()->username);
$user = get_user_by_username($username);
$filter = get_input("filter", "all");

$page_owner = get_user_by_username($username);
elgg_set_page_owner_guid($page_owner->getGUID());


if($filter == 'media')
$subtype = 'kaltura_video';
elseif ($filter == 'images')
$subtype = 'album';
elseif ($filter == 'files')
$subtype = 'file';
else
$subtype = 'archive';

$options = array( 'type' => 'object',
                  'subtype' => $subtype,
                  'owner_guid' => $user->guid,
                  'limit' => $limit,
                  'offset' => $offset,
                  'full_view' => false
                );

$content = elgg_list_entities($options);


elgg_register_menu_item('title', array('name'=>'upload', 'text'=>elgg_echo('upload'), 'href'=>'archive/upload','class'=>'elgg-button elgg-button-action'));
$vars['filter_context'] = 'mine';
$body = elgg_view_layout(	"gallery", array(
												'content' => $content, 
												'sidebar' => $sidebar, 
												'title' => elgg_echo('archive'),
												'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
											));

	// Display page
echo elgg_view_page(elgg_echo('kalturavideo:label:adminvideos'),$body);

?>
