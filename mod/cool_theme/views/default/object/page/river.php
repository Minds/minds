<?php

$page = $vars['entity'];

$icon = elgg_view_entity_icon($page, 'tiny');

echo elgg_view('river/elements/attachment', array(
	'image' => $icon,
	'title' => elgg_view('output/url', array(
		'href' => $page->getURL(), 
		'text' => $page->title,
		'encode_text' => true,
	)),
	'description' => elgg_get_excerpt($page->description, 1000),
));