<?php

$file = $vars['entity'];

$title = elgg_view('output/url', array(
	'text' => $file->title,
	'href' => $file->getURL(),
	'encode_text' => true,
));

$description = elgg_get_excerpt($file->description, 350);

echo elgg_view('river/elements/attachment', array(
	'image' => elgg_view_entity_icon($file, 'small'),
	'title' => $title,
	'description' => $description,
));