<?php

global $CONFIG;


$ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;

// Work out which domain
$domain_at_minds = get_input('domain_at_minds');
$my_domain = get_input('my_domain');
$email = get_input('email');


try {
    if (!$email)
        throw new Exception("Email address must be entered.");
    
    if ($domain_at_minds || $my_domain) {

        // Work out which domain
        $domain = $domain_at_minds . $ROOT_DOMAIN;
        if ($my_domain)
            $domain = $my_domain;

        // Check whether node exists
        $exists = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/get_domain_exists.php?domain=' . $domain));
        
        if (!$exists) 
            throw new Exception("Minds multisite could not be reached, please try again later");
        if (!$exists->success)
            throw new Exception($exists->message);
        
        if ($exists->exists == true)
            throw new Exception("Sorry, domain $domain has already been registered"); // Exists

        
        // We now have to have a minds user to link payment to, so we get the logged in user
        $owner_user = elgg_get_logged_in_user_entity();
        
        // Find what tier we're on (note, we use the product code not the guid so its meaningful to the multisite node)
        $ia = elgg_set_ignore_access();
        $order = minds_tiers_get_current_valid_tier($owner_user);
        if ($order->payment_used) throw new Exception("Order has already been used to create a network.");
        $tier = get_entity($order->object_guid);
        $tier_id = $tier->product_id;
        if (!$tier) throw new Exception('No tier bought by user!');
        
        elgg_set_ignore_access($ia);
        
        // We have explicity not got a domain existing already
        // 
        // 
        // Create a new minds account
            
        // Create sanitised username
        $username = preg_replace("/[^a-zA-Z0-9\-\.\s]/", "", $domain);
        $username = str_replace('.','_', $username);
        $username = str_replace('-','_', $username);
        
        // Create other basic user info
        $password = $password2 = generate_random_cleartext_password();
        $name = $username;
        
        // Register the user
        $minds_user_id = register_user($username, $password, $name, $email, true);
        if (!$minds_user_id)
            throw new Exception("There was a problem creating your minds account.");
        
        // Register a node
        $results = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/add_domain.php?domain=' . $domain . '&minds_user_id=' . $minds_user_id . '&tier=' . $tier_id));
        if (!$results)
            throw new Exception("Minds multisite could not be reached while registering your domain, please try again later");
        if (!$results->new_domain_id)
            throw new Exception("Error creating database for the new minds node");
        if ((!$results->tier) || ($results->tier!=$tier_id))
            throw new Exception("Could not set tier $tier_id on new minds node");
        if (!$results->success)
            throw new Exception($results->message);
        
        // Now, we create an association
        add_entity_relationship($owner_user, 'owned_multisite_networks', $minds_user_id);
        
        // And say we've used the order
        $order->payment_used = time();
                
        system_message("New minds network $domain successfully created!");
        forward(elgg_get_site_url() . "register/testping?domain=$domain");
    }
    else
        throw new Exception("You must specify a node or domain");
} catch (Exception $e) {
    register_error($e->getMessage());
}
