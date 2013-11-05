<?php

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;

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
   
   // set up tier indexes
   run_function_once('minds_tier_runone_2013110501');
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
    global $DB;
    
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

    $namespace = 'object:pay';

    //$slice = new ColumnSlice(0, "", 1, true);//set to reversed
    //$guids = $DB->cfs['entities_by_time']->get($namespace, $slice);
    $results = $DB->cfs['object']->get_indexed_slices(new IndexClause(
            array(
                new IndexExpression('subtype', 'pay'),
                new IndexExpression('status', 'Completed'),
                new IndexExpression('object_guid', $tiers_guid),
                new IndexExpression('owner_guid', $user->guid),
            )
    ));
    
    $order = array();
    if ($results) {
        
        foreach ($results as $k => $r) {
            $r['guid'] = $k;
            $new_row = new StdClass;

            foreach($r as $rk=>$rv){
                    $new_row->$rk = $rv;
            }
            
            $order[] = entity_row_to_elggstar($new_row, 'object');
        }
    
        // Sort our orders
        usort($order, function($a, $b)
        {
            if ($a->time_updated == $b->time_updated) {
                return 0;
            }
            return ($a->time_updated > $b->time_updated) ? -1 : 1;
        });
    }
    
    /*        
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
*/
   
    
   if (count($order)) {
       
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


function minds_tier_runone_2013110501() {
    global $CONFIG;
   
    $sys = new phpcassa\SystemManager($CONFIG->cassandra->servers[0]);

    $sys->create_index($CONFIG->cassandra->keyspace, 'object', 'status', 'UTF8Type');
    $sys->create_index($CONFIG->cassandra->keyspace, 'object', 'object_guid', 'IntegerType');
}

elgg_register_event_handler('init', 'system', 'minds_tiers_init');

define('MINDS_EXPIRES_DAY', 86400);
define('MINDS_EXPIRES_WEEK', 604800);
define('MINDS_EXPIRES_MONTH', 2419200);
define('MINDS_EXPIRES_YEAR', 31536000);