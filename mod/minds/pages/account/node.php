<?php
    
$user = elgg_get_logged_in_user_entity();
    
    
$title = elgg_echo("register:node");

$content = elgg_view_title($title);

// Display original form for logged in users
//if (elgg_is_admin_logged_in()) {
if (false) {
    
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
    
    
    // Check to see if there is a paid for tier product
    if ($user) {
        $tiers_guid = array();
        $ia = elgg_set_ignore_access();
    
        // Get tiers
        if ($tiers = elgg_get_entities(array(
           'type' => 'object',
            'subtype' => 'minds_tier'
        )))
        {
            foreach ($tiers as $tier)
                $tiers_guid[] = $tier->guid;
        }
       
        elgg_set_ignore_access($ia);
        
        
        $order = elgg_get_entities_from_metadata(array(
            'type' => 'object',
            'subtype' => 'pay',
            'owner_guid' => $user->guid,
             'metadata_name_value_pairs' => array(
                array('name' => 'status', 'value' => 'Completed'), // Interested in completed payments
                array('name' => 'object_guid', 'value' => $tiers_guid) // Which are valid tiers
                 
                 
                 // Note, tier is considered valid until its status is set to something other than Completed, e.g. 'Cancelled'
                 
                ),
        ));
        
        if ($order)
            $payment_received = true; 
    }
    
    
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

$title_block = elgg_view_title($title, array('class' => 'elgg-heading-main'));
$header = <<<HTML
<div class="elgg-head clearfix">
        $title_block
</div>
HTML;

    
$body = elgg_view_layout("one_column", array('content' => '<div class="elgg-inner">'. $content . ' <br/> '.  $buttons .'</div>', 'header'=>$header));

echo elgg_view_page($title, $body);
