<?php

 if(!class_exists('MindsTrending')){
                forward(REFERRER);
        }


// Get the current page's owner
$page_owner = elgg_get_logged_in_user_guid();
elgg_set_page_owner_guid($page_owner);

$limit = get_input("limit", 8);
$offset = get_input("offset", 0);
$filter = get_input("filter", "all");


if($filter == 'media')
$subtype = 'kaltura_video';
elseif ($filter == 'images')
$subtype = 'image';
elseif ($filter == 'files')
$subtype = 'file';
else
$subtype = 'archive';

//trending
$options = array(
	'timespan' => get_input('timespan', 'day')
);
$trending = new MindsTrending(null, $options);
$guids = $trending->getList(array('type'=>'object', 'subtype'=>'kaltura_video', 'limit'=>$limit, 'offset'=>$offset));//no super subtype support atm
 
$content = elgg_list_entities(	array(	'guids' => $guids,
					'full_view' => FALSE,
					'archive_view' => TRUE,
					'limit'=>$limit,
					'offset' => 0,
					'pagination_legacy' => true
		));

$context = 'archive';
 
elgg_register_menu_item('title', array('name'=>'upload', 'text'=>elgg_echo('upload'), 'href'=>'archive/upload','class'=>'elgg-button elgg-button-action'));

$vars['filter_context'] = 'trending';
$body = elgg_view_layout(	"gallery", array(
							'content' => $content, 
							'sidebar' => $sidebar,		
							'title' => elgg_echo('archive'),
							'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars)
											));

// Display page
echo elgg_view_page(elgg_echo('kalturavideo:label:adminvideos'),$body);

?>
