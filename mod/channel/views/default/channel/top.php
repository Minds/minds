<?php
/**
 * View to be loaded before the widget layout 
 * 
 * 
 */

 
$context = elgg_get_context();

$user = elgg_get_page_owner_entity();

echo elgg_view_title($user->name);

if (elgg_can_edit_widget_layout($context)) {
	
	echo elgg_view('output/url', array(	'href' => '#',
										'text' => 'testing',
										'class' => 'elgg-button elgg-button-action channel'
									));

}