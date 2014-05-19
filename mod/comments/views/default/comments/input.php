<?php

elgg_load_js('jquery.autosize');
$input_text = elgg_view('input/plaintext', array(
    'name' => 'comment',
    'class' => 'comments-input',
    'placeholder' => 'Enter your comment here...'
));
		
if(elgg_is_logged_in()){
	$user = elgg_get_logged_in_user_entity();
} else {
	$user = get_user_by_username('minds');
}

if($user){
	$icon = elgg_view_entity_icon($user, 'tiny');
}

$form_body .= elgg_view_image_block($icon, $input_text);

$form_body .= elgg_view('input/submit', array(
    'value' => 'submit',
    'class' => 'hidden'
        ));

$form = elgg_view('input/form', array(
    'body' => $form_body,
    'enctype' => 'application/json',
    'action' => 'comments/entity/'.$vars['parent_guid'],
    'class' => 'minds-comments-form'
        ));

echo $form;