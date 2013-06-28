<?php

/**
 * Minds Tiers
 * Define products and allow users to buy them
 *
 * @link http://www.marcus-povey.co.uk
 * @author Marcus Povey <marcus@marcus-povey.co.uk>
 * @copyright Minds Ltd
 */
function minds_tiers_init() {

    // Register action
    elgg_register_action('minds/products/new', dirname(__FILE__) . '/actions/new.php', 'admin');
    elgg_register_action('minds/products/delete', dirname(__FILE__) . '/actions/delete.php', 'admin');

    // Register an admin menu
    elgg_register_admin_menu_item('minds', 'products');
    elgg_register_admin_menu_item('minds', 'manage', 'products');
}


function minds_tiers_get_product($product_id) {
    $access = elgg_set_ignore_access();
    $products = elgg_get_entities_from_metadata(array(
        'types' => array('object'),
        'subtypes' => array('Minds_product'),
        'metadata_name_value_pairs' => array(
            'name' => 'product_id',
            'value' => $product_id,
        ),
    ));
    elgg_set_ignore_access($access);
    if (!$products)
        throw new Exception("No product $product_id found");

    return $products[0];
}

/**
 * Looks at products and sees if a user has paid for a tier, which has not expired, returning the payment details
 * if so.
 * @param type $user
 */
function minds_tiers_get_current_valid_tier($user) {
    if (!$user) $user = elgg_get_logged_in_user_entity ();
    if (!$user) return false;
    
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

    $order = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'pay',
        'owner_guid' => $user->guid,
         'metadata_name_value_pairs' => array(
            array('name' => 'status', 'value' => 'Completed'), // Interested in completed payments
            array('name' => 'object_guid', 'value' => $tiers_guid) // Which are valid tiers


             // Note, tier is considered valid until its status is set to something other than Completed, e.g. 'Cancelled', or expired (see below)

            ),
    ));

   
    
   if ($order) {
       
       foreach ($order as $o)
       {
           $t = get_entity($o->object_guid);
           
           $expires = $t->expires;
           if (!$expires) $expires = MINDS_EXPIRES_YEAR; // Default to year
           
           // If cost is 0, then never expire 
           if ($t->price == 0) {
               elgg_set_ignore_access($ia);
               return $o;
           }
           
           if ($o->time_updated >= (time() - $expires))
           {
                elgg_set_ignore_access($ia);
                return $o;
           }
       }

   }

   
   elgg_set_ignore_access($ia);
   return false;
}


elgg_register_event_handler('init', 'system', 'minds_tiers_init');

define('MINDS_EXPIRES_DAY', 86400);
define('MINDS_EXPIRES_WEEK', 604800);
define('MINDS_EXPIRES_MONTH', 2419200);
define('MINDS_EXPIRES_YEAR', 31536000);