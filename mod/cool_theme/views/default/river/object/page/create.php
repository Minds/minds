<?php
/**
 * Page river view.
 */

$object = $vars['item']->getObjectEntity();

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'attachments' => elgg_view('object/page/river', array('entity' => $object)),
));