<?php

$title = '';
$subtitle = false;
$image = false;
$description = false;

extract($vars, EXTR_IF_EXISTS);

$body = "<h4 class=\"elgg-attachment-title\">$title</h4>";

if ($subtitle) {
	$body .= "<div class=\"elgg-attachment-subtitle\">$subtitle</div>";
}

if ($description) {
	$body .= "<div class=\"elgg-attachment-description\">$description</div>";
}


if ($image) {
	echo elgg_view_image_block($image, $body);
} else {
	echo $body;
}