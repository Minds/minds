<?php

$tagger = get_entity($vars['item']->subject_guid);
$tagged = get_entity($vars['item']->object_guid);
$annotation = get_annotation($vars['item']->annotation_id);
if ($annotation) {
	$image = get_entity($annotation->entity_guid);

	// viewer may not have permission to view image
	if (!$image) {
		return;
	}

	$image_title = $image->getTitle();
}

$tagger_link = "<a href=\"{$tagger->getURL()}\">$tagger->name</a>";
$tagged_link = "<a href=\"{$tagged->getURL()}\">$tagged->name</a>";
if (!empty($image_title)) {
	$image_link = "<a href=\"{$image->getURL()}\">$image_title</a>";
	$string = sprintf(elgg_echo('image:river:tagged'), $tagger_link, $tagged_link, $image_link);
} else {
	$string = sprintf(elgg_echo('image:river:tagged:unknown'), $tagger_link, $tagged_link);	
}

echo $string;
