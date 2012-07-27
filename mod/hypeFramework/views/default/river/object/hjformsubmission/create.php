<?php
/**
 * Default Form Submission River
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->excerpt);
$excerpt = elgg_get_excerpt($excerpt);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
        'attachments' => elgg_view_entity($object, array('full_view' => false))
));
