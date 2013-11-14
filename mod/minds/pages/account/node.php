<?php
    
$user = elgg_get_logged_in_user_entity();
    
    
$title = elgg_echo("register:node");

$content = elgg_view_title($title);

// Display original form for logged in users
//if (elgg_is_admin_logged_in()) {
/*if (true) {

	$content = '<h3>Launching a social network will be available shortly.</h3> Please check back soon for more information';    
  
/*} else {
  */  
    $payment_recieved = false; 
    
    // Check to see if there is a paid for tier product
    if ($user) {
        
        if (is_callable('minds_tiers_get_current_valid_tier'))
            $order = minds_tiers_get_current_valid_tier($user);
        
        if ($order)
            $payment_received = true; 
    }
    
    
    // If paid for then allow registration
    if ($payment_received) {

        $register_url = elgg_get_site_url() . 'action/registernewnode';
        $form_params = array(
                'action' => $register_url,
                'class' => 'elgg-form-account',
        );

        $body_params = array(
            'order' => $order
        );
        $content = elgg_view_form('node', $form_params, $body_params);
    } else {
        //allow multiple nodes per account
	$register_url = elgg_get_site_url() . 'action/select_tier';
        $form_params = array(
                'action' => $register_url,
                'class' => 'elgg-form-account',
        );

        $body_params = array(
        );
        $content = elgg_view_form('select_tier', $form_params, $body_params);
    }
//}

$title_block = elgg_view_title($title, array('class' => 'elgg-heading-main'));
$header = <<<HTML
<div class="elgg-head clearfix">
        $title_block
</div>
HTML;

    
$body = elgg_view_layout("one_column", array(	'content' => '<div class="elgg-inner">'. $content . ' <br/> '.  $buttons .'</div>', 
						'header'=>$header,
					));

echo elgg_view_page($title, $body);
