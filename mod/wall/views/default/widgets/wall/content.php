<?php
/**
 * Wall widget
 *
 */

elgg_load_js('elgg.wall');

$owner = elgg_get_page_owner_entity();

$num_display = $vars['entity']->num_display;

if (elgg_is_logged_in()) {
	echo elgg_view_form('wall/add', array('name' => 'elgg-wall'), array('to_guid'=>$owner->guid));
}

$options = array(
	'types' => 'object',
	'subtypes' => 'wallpost',
	'limit' => 10,
	'metadata_name_value_pairs' => array('name'=>'to_guid', 'value'=> $owner->guid),
	'reverse_order_by' => false,
	'full_view'=>false,
	'pagination' => false
);

echo elgg_list_entities_from_metadata($options);

if ($owner instanceof ElggGroup) {
	$url = "messageboard/group/$owner->guid/all";
} else {
	$url = "wall/$owner->username";
}

echo elgg_view('output/url', array(
	'href' => $url,
	'text' => elgg_echo('wall:viewall'),
	'is_trusted' => true,
));