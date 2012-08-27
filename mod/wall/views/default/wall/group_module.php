<?php
/**
 * Minds Wall Plugin
 *
 * @author John Mellberg <big_lizard_clyde@hotmail.com>
 * @package Wall
 *
 */

$group = elgg_get_page_owner_entity();

elgg_load_js('elgg.wall');

if ($group->polls_enable == 'yes'){
	elgg_push_context('widgets');
	
	
	$all_link = elgg_view('output/url', array(
		'href' => "discussion/owner/$group->guid",
		'text' => elgg_echo('link:view:all'),
		'is_trusted' => true,
	));
	
	$content = elgg_view_form('wall/add', array('name' => 'elgg-wall'), array('to_guid'=>$group->guid));
	
	$limit = 4;
	$options = array(
		'types' => 'object',
		'subtypes' => 'wallpost',
		'limit' => $limit,
		'metadata_name_value_pairs' => array('name'=>'to_guid', 'value'=> $group->guid),
		'reverse_order_by' => false,
		'full_view'=>false,
		'pagination' => false
	);

	$content .= elgg_list_entities_from_metadata($options);
		
	elgg_pop_context();
	if (!$content) {
	  $content .= '<p>'.elgg_echo("group:wall:empty").'</p>';
	}
	
	echo elgg_view('groups/profile/module', array(
		'title' => elgg_echo('wall:group_wall'),
		'content' => $content,
		'all_link' => $all_link,
		//'add_link' => $new_link,
	));
}
