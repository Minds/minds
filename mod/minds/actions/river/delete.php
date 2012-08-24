<?php
/**
 * River item delete action
 *
 * @package Elgg
 * @subpackage Core
 */

$id = get_input('id', false);

$items = elgg_get_river(array('ids'=>$id));
$item = $items[0];
$object = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
	

if ($id !== false && ($object->canEdit() || $subject->canEdit())) {
	if (elgg_delete_river(array('id' => $id))) {
		system_message(elgg_echo('river:delete:success'));
	} else {
		register_error(elgg_echo('river:delete:fail'));
	}
} else {
	register_error(elgg_echo('river:delete:fail'));
}

forward(REFERER);
