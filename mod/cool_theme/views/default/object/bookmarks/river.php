<?php

$link = $vars['entity'];

$title = elgg_view('output/url', array(
	'text' => $link->title,
	'href' => $link->getURL(),
	'encode_text' => true,
));

$subtitle = elgg_view('output/url', array('value' => $link->address));

$description = elgg_get_excerpt($link->description, 350);

echo elgg_view('river/elements/attachment', array(
	'title' => $title,
	'subtitle' => $subtitle,
	'description' => $description,
));