<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
    return true;
}

$form_body .= elgg_view('input/text', array(
    'name' => 'annotation_value'
        ));

$form_body .= elgg_view('input/hidden', array(
    'name' => 'aname',
    'value' => elgg_extract('aname', $vars, 'generic_comment')
        ));

if ($container_guid = elgg_extract('container_guid', $vars, false)) {
    $form_body .= elgg_view('input/hidden', array(
        'name' => 'container_guid',
        'value' => $container_guid
            ));
}

if ($river_id = elgg_extract('river_id', $vars, false)) {
$form_body .= elgg_view('input/hidden', array(
    'name' => 'river_id',
    'value' => $river_id
        ));
}

if (!$access = $entity->access_id) {
    $access = ACCESS_DEFAULT;
}
$form_body .= elgg_view('input/hidden', array(
    'name' => 'access_id',
    'value' => $access
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