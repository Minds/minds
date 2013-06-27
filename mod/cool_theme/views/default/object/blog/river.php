<?php

$blog = $vars['entity'];

$title = elgg_view('output/url', array(
	'text' => $blog->title,
	'href' => $blog->getURL(),
	'encode_text' => true,
));

$description = elgg_get_excerpt($blog->description, 350);

echo elgg_view('river/elements/attachment', array(
	'title' => $title,
	'description' => $description,
	'image' => elgg_view_entity_icon($blog->getOwnerEntity(), 'tiny'),
));