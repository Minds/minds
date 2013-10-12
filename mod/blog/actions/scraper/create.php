<?php

// edit or create a new entity
$guid = get_input('guid');

if ($guid) {
	$entity = get_entity($guid,'object');
	if (elgg_instanceof($entity, 'object', 'scraper') && $entity->canEdit()) {
		$scraper = $entity;
	} else {
		register_error(elgg_echo('blog:error:post_not_found'));
		forward(get_input('forward', REFERER));
	}
} else {
	$scraper = new MindsScraper();
}

if(get_input('license') == 'not-selected'){
	register_error('A license must be selected');
	forward(get_input('forward', REFERER));
}

$scraper->title = get_input('title');
$scraper->feed_url = get_input('url');
$scraper->license = get_input('license');

if($scraper->save()){
	forward('blog/scrapers/mine');
} else {
	
	register_error('there was problem');
	forward(REFERRER);
	
}
