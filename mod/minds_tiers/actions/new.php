<?php
    
    action_gatekeeper();
    admin_gatekeeper();
    
    $title = get_input('title');
    $description = get_input('description');
    $currency = get_input('currency', 'GBP');
    $price = get_input('price');
   // $expires = get_input('expires', Minds_EXPIRES_YEAR); // Lifetime in seconds, or never
    $product_id = get_input('product_id');
    
    
    /** Create a new product */
    $product = new ElggObject();
    $product->subtype = 'minds_tier';
    $product->owner_guid = elgg_get_logged_in_user_guid();
    $product->container_guid = elgg_get_logged_in_user_guid();
    $product->access_id = ACCESS_LOGGED_IN;
    $product->title = $title;
    $product->description = $description;
    $product->currency = $currency;
    $product->price = $price;
    //$product->expires = $expires;
    $product->product_id = preg_replace("/[^a-zA-Z0-9\s_]/", "", $product_id);
    
    if ($product->save()) 
        system_message ('New product created');
    else
        register_error ('There was a problem saving the product');
    
    forward(REFERER);