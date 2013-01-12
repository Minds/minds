<?php

$type = elgg_extract('type', $vars, false);
$pid = elgg_extract('pid', $vars, false);

elgg_load_js('jquery.autosize');
$input_text = elgg_view('input/plaintext', array(
    'name' => 'comment',
    'class' => 'comments-input',
        ));
$icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny');

$form_body .= elgg_view_image_block($icon, $input_text);


$form_body .= elgg_view('input/hidden', array(
    'name' => 'type',
    'value' => $type
        ));

$form_body .= elgg_view('input/hidden', array(
    'name' => 'pid',
    'value' => $pid,
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