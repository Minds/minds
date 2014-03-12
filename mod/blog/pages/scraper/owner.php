<?php
/**
 * Show all scrapers for a user
 */

if($username = get_input('username')){
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}
$page_owner = $user;

$scrapers = elgg_get_entities(array(	'type'=>'object', 
					'subtypes'=>array('scraper'),
					'owner_guid'=>$page_owner->guid,
					'limit'=>get_input('limit', 10),
					'offset' => get_input('offset', '')
										));
							
$title = elgg_echo('blog:minds:scraper');

$content = elgg_view_entity_list($scrapers);

elgg_register_menu_item('title', array(
			'name' => 'create_scraper',
			'href' => 'blog/scrapers/create',
			'text' => elgg_echo('blog:minds:scraper:create'),
			'link_class' => 'elgg-button elgg-button-action',
	));

$body = elgg_view_layout('content', array(	'title' => $title,
											'content'=>$content,
						'filter' => '',
						'class'=>'scrapers'
											));

										

echo elgg_view_page($title, $body);
