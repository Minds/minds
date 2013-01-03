<?php
/**
 * Minds ReMind for external content via search
 * 
 * In this case, reminding should add that content to the users Archive. 
 * 
 * @author Mark Harding (mark@minds.com)
 * 
 */

gatekeeper();

$item_id = get_input('item_id');

$search = new MindsSearch();
$item = $search->search('id:'.$item_id);
$item = $item['hits']['hits'][0];

$img_url = $item['_source']['iconURL'];
$source = $item['_source']['source'];
$source_href = $item['_source']['href'];

$post = new ElggObject();
$post->subtype = 'remind_external';
$post->owner_guid = elgg_get_logged_in_user_guid();
$post->access_id = ACCESS_DEFAULT;
$post->id = $item['_source']['id'];
$post->title = $item['_source']['title'];
$post->img_url = $item['_source']['iconURL'];
$post->source = $source;
$post->source_href = $source_href;

$guid = $post->save();
add_to_river('river/external/search/remind', 'remind', elgg_get_logged_in_user_guid(), $guid);
//add_entity_relationship($guid, 'remind', elgg_get_logged_in_user_guid()); 

system_message(elgg_echo("minds:remind:success"));

forward(REFERRER);