<?php
    
    $user = elgg_get_logged_in_user_entity();
    
    
$title = elgg_echo("register:node");

$content = elgg_view_title($title);

// Display original form for logged in users
if ($user) {
    
    if (!elgg_is_admin_logged_in())
        forward(); // Only admins can access logged in register form stuff now, since we're taking payment etc

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
    
    $payment_recieved = false; // TODO: Test to see if we've returned from a successful billing workflow
    
    // TODO: Payment hook here
    
    
    
    
    // If paid for then allow registration
    if ($payment_recieved) {

        $register_url = elgg_get_site_url() . 'action/registernewnode';
        $form_params = array(
                'action' => $register_url,
                'class' => 'elgg-form-account',
        );

        $body_params = array(
        );
        $content .= elgg_view_form('node', $form_params, $body_params);
    }
    else
    {
        $register_url = elgg_get_site_url() . 'action/select_tier';
        $form_params = array(
                'action' => $register_url,
                'class' => 'elgg-form-account',
        );

        $body_params = array(
        );
        $content .= elgg_view_form('select_tier', $form_params, $body_params);
    }
    
}


    
$body = elgg_view_layout("one_column", array('content' => $content));

echo elgg_view_page($title, $body);