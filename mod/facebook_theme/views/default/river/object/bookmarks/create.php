<?php
/**
 * New bookmarks river entry
 *
 * @package Bookmarks
 */

$object = $vars['item']->getObjectEntity();


echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'attachments' => elgg_view('object/bookmarks/river', array('entity' => $object)),
));