<?php
/**
 * New file river entry
 *
 * @package File
 */

$object = $vars['item']->getObjectEntity();

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'attachments' => elgg_view('object/file/river', array('entity' => $object)),
));