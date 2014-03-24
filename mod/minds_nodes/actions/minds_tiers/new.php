<?php
/**
 * Sets a new new tier
 */
    
action_gatekeeper();
admin_gatekeeper();

$guid = get_input('guid');

$title = get_input('title');
$description = get_input('description');
$currency = get_input('currency', 'GBP');
$price = get_input('price');
$expires = get_input('expires', MINDS_EXPIRES_YEAR); // Lifetime in seconds
$tier_id = get_input('product_id');
$allowed_domain = get_input('allowed_domain', 'yes');

/** Create a new product */
if (!($tier = get_entity($guid,'object'))) {
    $tier = new MindsTier();
    $tier->subtype = 'minds_tier';
    $tier->owner_guid = elgg_get_logged_in_user_guid();
    $tier->container_guid = elgg_get_logged_in_user_guid();
    $tier->access_id = ACCESS_PUBLIC;
}

$tier->title = $title;
$tier->description = $description;
$tier->currency = $currency;
$tier->price = $price;
$tier->expires = $expires;
$tier->product_id = preg_replace("/[^a-zA-Z0-9\s_]/", "", $tier_id);
$tier->allowed_domain = $allowed_domain;

if ($tier->save()) 
    system_message ('Product details saved');
else
    register_error ('There was a problem saving the product');

forward(elgg_get_site_url() . 'admin/minds_tiers/manage');
