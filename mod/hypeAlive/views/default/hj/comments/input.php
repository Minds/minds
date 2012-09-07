<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
    return true;
}

$input_text = elgg_view('input/text', array(
    'name' => 'annotation_value'
        ));
$icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny');

$form_body .= elgg_view_image_block($icon, $input_text);


$form_body .= elgg_view('input/hidden', array(
    'name' => 'aname',
    'value' => elgg_extract('aname', $vars, 'generic_comment')
        ));

if ($entity->action_type != 'create' && $entity->getType() == 'river') {
$form_body .= elgg_view('input/hidden', array(
    'name' => 'river_id',
    'value' => $entity->id
        ));
$form_body .= elgg_view('input/hidden', array(
    'name' => 'parent_guid',
    'value' =>  $entity->id
         ));
} else {
	if ($parent_guid = elgg_extract('parent_guid', $vars, false)) {
    $form_body .= elgg_view('input/hidden', array(
        'name' => 'parent_guid',
        'value' => $parent_guid
            ));
}
}

if (!$access = $entity->access_id) {
    $access = ACCESS_DEFAULT;
}
$form_body .= elgg_view('input/hidden', array(
    'name' => 'access_id',
    'value' => $access
        ));
		
$form_body .= elgg_view('input/hidden', array(
    'name' => 'object_guid',
    'value' => $entity->object_guid,
        ));
$form_body .= elgg_view('input/hidden', array(
    'name' => 'subject_guid',
    'value' => $entity->subject_guid,
        ));

$form_body .= elgg_view('input/submit', array(
    'value' => 'submit',
    'class' => 'hidden'
        ));

$form = elgg_view('input/form', array(
    'body' => $form_body,
    'enctype' => 'application/json',
    'action' => 'action/comment/save',
    'class' => 'hj-ajaxed-comment-save'
        ));

echo $form;