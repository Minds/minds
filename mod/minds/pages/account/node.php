<?php
    
    $user = elgg_get_logged_in_user_entity();
    
    
$title = elgg_echo("register:node");

$content = elgg_view_title($title);

// Display original form for logged in users
//if ($user) {
if (!$user) {

    // create the registration url - including switching to https if configured
    $register_url = elgg_get_site_url() . 'action/registernode';
    $form_params = array(
            'action' => $register_url,
            'class' => 'elgg-form-account',
    );

    $body_params = array(
            'minds_user_guid' => $user->guid,
    );
    $content .= elgg_view_form('node_loggedin', $form_params, $body_params);
} else {
    
    $register_url = elgg_get_site_url() . 'action/registernewnode';
    $form_params = array(
            'action' => $register_url,
            'class' => 'elgg-form-account',
    );

    $body_params = array(
    );
    $content .= elgg_view_form('node', $form_params, $body_params);
    
}


    
$body = elgg_view_layout("one_column", array('content' => $content));

echo elgg_view_page($title, $body);