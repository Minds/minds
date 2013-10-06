<?php
/**
 * River item delete action
 *
 * @package Elgg
 * @subpackage Core
 */

$id = get_input('id', false);

$items = elgg_get_river(array('type'=>'newsfeed', 'ids'=>$id));

$item = $items[0];
$object = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
	

if ($id !== false && ($object->canEdit() || $subject->canEdit())) {
	//delete the object if its a create post
	if($item->action_type == 'create'){
		$object->delete(); //this deletes the river item too!
		system_message(elgg_echo('river:delete:success'));
	} else {
		if (minds_elastic_delete_news(array('ids' => array($id)))) {
			system_message(elgg_echo('river:delete:success'));
		} else {
			register_error(elgg_echo('river:delete:fail'));
		}
	}
} else {
	register_error(elgg_echo('river:delete:fail'));
}

forward(REFERER);
