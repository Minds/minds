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

elgg_register_event_handler('init', 'system', 'minds_tiers_init');
