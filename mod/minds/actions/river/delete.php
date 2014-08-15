<?php
/**
 * River item delete action
 *
 * @package Elgg
 * @subpackage Core
 */

$id = get_input('id', false);


$item = new ElggRiverItem($id);
$object = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
	

if($item->subject_guid == elgg_get_logged_in_user_guid() || $item->object_guid == elgg_get_logged_in_guid()) {
	$item->delete();
}

forward(REFERER);
