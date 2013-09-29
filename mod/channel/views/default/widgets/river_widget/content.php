<?php
/**
 * Activity widget content view
 */

$num = (int) $vars['entity']->num_display;

$options = array(
	'limit' => $num,
	'pagination' => true,
);

$options['owner_guid'] = 'personal:'.elgg_get_page_owner_guid();

elgg_load_js('elgg.wall');
			
$wall_input = elgg_view_form('wall/add', array('name'=>'elgg-wall-news'), array('to_guid'=> elgg_get_page_owner_guid(), 'ref'=>'news'));

$content = elgg_view_module('wall', null, $wall_input);

$content .= elgg_list_river($options);

if (!$content) {
	$content = elgg_echo('river:none');
}

echo $content;
