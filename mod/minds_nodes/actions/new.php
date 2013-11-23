<?php
    
    action_gatekeeper();
    admin_gatekeeper();
    
    $guid = get_input('guid');
    
    $title = get_input('title');
    $description = get_input('description');
    $currency = get_input('currency', 'GBP');
    $price = get_input('price');
    $expires = get_input('expires', MINDS_EXPIRES_YEAR); // Lifetime in seconds
    $product_id = get_input('product_id');
    $allowed_domain = get_input('allowed_domain', 'yes');
    
    /** Create a new product */
    if (!($product = get_entity($guid,'object'))) {
        $product = new ElggObject();
        $product->subtype = 'minds_tier';
        $product->owner_guid = elgg_get_logged_in_user_guid();
        $product->container_guid = elgg_get_logged_in_user_guid();
        $product->access_id = ACCESS_LOGGED_IN;
    }
    
    $product->title = $title;
    $product->description = $description;
    $product->currency = $currency;
    $product->price = $price;
    $product->expires = $expires;
    $product->product_id = preg_replace("/[^a-zA-Z0-9\s_]/", "", $product_id);
	$product->allowed_domain = $allowed_domain;
    
    if ($product->save()) 
        system_message ('Product details saved');
    else
        register_error ('There was a problem saving the product');
    
    forward(elgg_get_site_url() . 'admin/products/manage');
