<?php

$group = $vars['entity'];

$image = elgg_view_entity_icon($group, 'tiny');

$title = elgg_view('output/url', array(
	'href' => $group->getURL(), 
	'text' => $group->name, 
	'encode_text' => true,
));


$subtitle = elgg_view('output/text', array('value' => $group->briefdescription));

$description = elgg_get_excerpt($group->description, 350);

echo elgg_view('river/elements/attachment', array(
	'icon' => $image,
	'title' => $title,
	'subtitle' => $subtitle,
	'description' => $description,
));