<?php

// Get the current page's owner
$page_owner = elgg_get_logged_in_user_guid();
elgg_set_page_owner_guid($page_owner);

$limit = get_input("limit", 8);
$offset = get_input("offset", 0);
$filter = get_input("filter", "all");

switch($filter){
    case "images":
    case "image":
        $prepared = new Minds\Core\Data\Neo4j\Prepared\Common();
        $result= Minds\Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects('image', get_input('offset', 0)));
        $rows = $result->getRows();
        
        $guids = array();
        foreach($rows['object'] as $object){
            $guids[] = $object['guid'];
        } 
        break;
    case "media":
    case "video";
        $prepared = new Minds\Core\Data\Neo4j\Prepared\Common();
        $result= Minds\Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects('video', get_input('offset', 0)));
        $rows = $result->getRows();

        $guids = array();
        foreach($rows['object'] as $object){
            $guids[] = $object['guid'];
        }
        break;
    case "all":
    default:
        forward("/archive/trending?filter=video");
        exit;
}

if(!$guids){
	forward('archive/all');	 
}
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
