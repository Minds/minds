<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

global $jsonexport;


if (!isset($jsonexport['activity'])) {
	$jsonexport['activity'] = array();
}

$item = $vars['item'];
$annotation = $vars['item']->getAnnotation();
$object = get_entity($item->object_guid);
$subject = get_entity($item->subject_guid);

if (elgg_view_exists($item->view, 'default')) {
	$item->string = elgg_view('river/elements/summary', array('item' => $item), FALSE, FALSE, 'default');
}

if($object->type == "user" || $object->type == "group"){
		$item->object_metadata['name'] = $object->name;
		$item->object_metadata['username'] = $object->username;
		$item->object_metadata['avatar_url'] = get_entity_icon_url($object,'medium');
		if($annotation){
		$item->object_metadata['message'] = $annotation->value;
		} 
		$item->object_metadata['description'] = $object->description;
	} else {
		$item->object_metadata['name'] = $object->title;
		if($annotation){
		$item->object_metadata['message'] = $annotation->value;
		} 
		$item->object_metadata['description'] = $object->description;

}

if($subject->type == "user" || $object->type == "group"){
		$item->subject_metadata['name'] = $subject->name;
		$item->subject_metadata['username'] = $subject->username;
		$item->subject_metadata['avatar_url'] = get_entity_icon_url($subject,'small');
	} else {
		$item->subject_metadata['name'] = $subject->title;
}


$jsonexport['activity'][] = $vars['item'];
