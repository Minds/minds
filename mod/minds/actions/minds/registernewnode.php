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
        $results = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/add_domain.php?domain=' . $domain . '&minds_user_id=' . $minds_user_id));
        if (!$results)
            throw new Exception("Minds multisite could not be reached while registering your domain, please try again later");
        if (!$results->success)
            throw new Exception($results->message);

        system_message("New minds network $domain successfully created!");
        forward(elgg_get_site_url() . "register/testping?domain=$domain");
    }
    else
        throw new Exception("You must specify a node or domain");
} catch (Exception $e) {
    register_error($e->getMessage());
}
